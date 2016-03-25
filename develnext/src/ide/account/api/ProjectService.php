<?php
namespace ide\account\api;

use Files;
use ide\Ide;
use ide\project\Project;
use ide\utils\FileUtils;
use php\compress\ArchiveOutputStream;
use php\io\File;
use php\io\IOException;
use php\io\MemoryStream;
use php\io\Stream;
use php\lang\IllegalStateException;
use php\lib\Items;
use php\lib\Str;
use php\util\Configuration;

/**
 * Class ProjectService
 * @package ide\account\api
 *
 * @method updateAsync(Project $project, callable $callback)
 * @method prepareSyncAsync(Project $project, callable $callback)
 * @method syncAsync(Project $project, $prepare, callable $callback)
 * @method array getAsync($projectId, callable $callback)
 * @method array getListAsync(callable $callback)
 */
class ProjectService extends AbstractService
{
    protected function getProjectId(Project $project)
    {
        $config = $project->getIdeConfig('project.ws');

        return $config->get('projectId', null);
    }

    public function get($id)
    {
        return $this->execute('project/get', ['id' => $id]);
    }

    public function getList()
    {
        return $this->execute('project/list', []);
    }

    public function isUploaded(Project $project)
    {
        $config = $project->getIdeConfig('project.ws');

        return !!$config->get('projectId', null);
    }

    public function update(Project $project)
    {
        $config = $project->getIdeConfig('project.ws');

        $projectId = $config->get('projectId', null);

        $result = $this->execute('project/update', [
            'id' => $projectId,
            'name' => $project->getName(),
            'ideName' => Ide::get()->getName(),
            'ideVersion' => Ide::get()->getVersion(),
        ]);

        $config->set('projectId', $result->data()['id']);
        $config->save();

        return $result;
    }

    public function prepareSync(Project $project)
    {
        $projectId = $this->getProjectId($project);

        if ($projectId == null) {
            throw new IllegalStateException("Project is not updated");
        }

        $zipFile = Ide::get()->createTempFile('.zip');
        $zip = new ArchiveOutputStream('zip', $zipFile);

        $exporter = $project->makeExporter();

        foreach ($exporter->getFiles() as $name => $filename) {
            if (Files::isFile($filename)) {
                $file = File::of($filename);

                $config = new Configuration();
                $config->set('projectId', $projectId);
                $config->set('length', $file->length());
                $config->set('crc32', $file->crc32());
                $config->set('hash', $file->hash('SHA-256'));

                $memory = new MemoryStream();
                $config->save($memory);

                $entity = $zip->createEntry($file, $name);
                $entity->setSize($memory->length());

                $memory->seek(0);

                $zip->addEntry($entity);
                $zip->write($memory->readFully());

                $zip->closeEntry();
            }
        }

        $zip->close();

        $result = $this->upload('project/prepare-sync', ['index' => "$zipFile"]);

        $zipFile->delete();

        return $result;
    }

    public function sync(Project $project, $prepare)
    {
        $projectId = $this->getProjectId($project);

        if ($projectId == null) {
            throw new IllegalStateException("Project is not updated");
        }

        $zipFile = Ide::get()->createTempFile('.zip');
        $zip = new ArchiveOutputStream('zip', $zipFile);

        $exporter = $project->makeExporter();

        // write .files
        $files = "projectId=$projectId\n";

        foreach ($exporter->getFiles() as $name => $filename) {
            if (Files::isFile($filename)) {
                $files .= "$name\n";
            }
        }

        $entity = $zip->createEntry('.files', '.files');
        $entity->setSize(Str::length($files));

        $zip->addEntry($entity);
        $zip->write($files);
        $zip->closeEntry();

        // write all
        foreach ($prepare as $name => $command) {
            $config = new Configuration();
            $config->set('projectId', $projectId);
            $config->set('command', $command);

            $memory = new MemoryStream();
            $config->save($memory);

            $entity = $zip->createEntry($name, $name);
            $entity->setSize($memory->length());

            $memory->seek(0);

            $zip->addEntry($entity);
            $zip->write($memory->readFully());

            $zip->closeEntry();
        }
        $zip->close();

        // write data
        $filesArchive = Ide::get()->createTempFile('.zip');
        $zip = new ArchiveOutputStream('zip', $filesArchive);

        foreach ($exporter->getFiles() as $name => $filename) {
            if (Files::isFile($filename)) {
                $contents = Stream::getContents($filename);

                $entity = $zip->createEntry($filename, $name);
                $entity->setSize(Str::length($contents));

                $zip->addEntry($entity);
                $zip->write($contents);

                $zip->closeEntry();
            }
        }

        $zip->close();

        $result = $this->upload('project/sync', ['index' => "$zipFile", 'files' => "$filesArchive"]);

        $zipFile->delete();
        $filesArchive->delete();
        return $result;
    }
}