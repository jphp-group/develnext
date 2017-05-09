<?php
namespace ide\account\api;
use ide\Logger;
use php\io\IOException;
use ide\Ide;
use ide\systems\FileSystem;
use php\io\FileStream;

/**
 * Class ProjectArchiveService
 * @package ide\account\api
 *
 * @method ServiceResponseFuture createAsync($name, $description, callable $callback = null)
 * @method ServiceResponseFuture uploadArchiveAsync($projectId, $file, callable $callback = null)
 * @method uploadNewAsync($file, callable $callback = null)
 * @method uploadOldAsync($id, $file, callable $callback = null)
 * @method updateAsync($id, $name, $description, callable $callback = null)
 * @method ServiceResponseFuture getAsync($uid, callable $callback = null)
 * @method deleteAsync($id, callable $callback = null)
 * @method getListAsync(callable $callback = null)
 */
class ProjectArchiveService extends AbstractService
{
    /**
     * @param $uid
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function get($uid)
    {
        return $this->executeGet("project/projects/$uid");
    }

    /**
     * @param string $name
     * @param string $description
     * @return ServiceResponse
     */
    public function create($name, $description)
    {
        return $this->execute("project/projects/create", ['name' => $name, 'description' => $description]);
    }

    /**
     * @param string $projectId
     * @param string $file
     * @return ServiceResponse
     */
    public function uploadArchive($projectId, $file)
    {
        return $this->upload("project/projects/$projectId/upload", ['file' => $file]);
    }

    public function download($uid, $downloadKey, $file)
    {
        $input = $this->getStream("project/projects/$uid/$downloadKey/download");

        $output = new FileStream($file, 'w+');

        try {
            while (($buff = $input->read(8096)) !== false) {
                $output->write($buff);
            }

            return true;
        } catch (IOException $e) {
            Logger::exception("Unable to download file", $e);
            return false;
        } finally {
            $input->close();

            if ($output) {
                $output->close();
            }
        }
    }

    /**
     * @param $uid
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function delete($uid)
    {
        return $this->execute("project/projects/$uid", [], 'DELETE');
    }

    /**
     * @param $id
     * @param string $file path to file
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function uploadOld($id, $file)
    {
        return $this->upload("project-archive/upload/$id", [
            'file' => $file
        ]);
    }


    /**
     * @param $file path to file
     * @return ServiceResponse
     */
    public function uploadNew($file)
    {
        return $this->upload('project-archive/upload', [
            'file' => $file
        ]);
    }

    /**
     * @return ServiceResponse
     */
    public function getList()
    {
        return $this->executeGet('project/projects/list/owner/' . Ide::accountManager()->getAccountData()['id']);
    }

    /**
     * @param $idProject
     * @param $name
     * @param $description
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function update($idProject, $name, $description)
    {
        return $this->execute('project-archive/update', [
            'id' => $idProject,
            'name' => $name,
            'description' => $description
        ]);
    }
}