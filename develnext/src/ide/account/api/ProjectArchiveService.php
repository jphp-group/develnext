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
 * @method uploadNewAsync($file, callable $callback = null)
 * @method uploadOldAsync($id, $file, callable $callback = null)
 * @method updateAsync($id, $name, $description, callable $callback = null)
 * @method getAsync($uid, callable $callback = null)
 * @method deleteAsync($id, $absolutely, callable $callback = null)
 * @method getDownloadUrlAsync($url, callable $callback = null)
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
        return $this->execute('project-archive/get', [
            'uid' => $uid
        ]);
    }

    /**
     * @param $url
     * @param $file
     * @return bool
     */
    public function downloadToFile($url, $file)
    {
        $url = $this->getDownloadUrl(Ide::service()->getEndpoint() . $url);

        if ($url) {
            $conn = $this->getUrlConnection(Ide::service()->getEndpoint() . $url);

            $input = $conn->getInputStream();
            $output = new FileStream($file, 'w+');

            try {
                while (($buff = $input->read(2048)) !== false) {
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
        } else {
            return false;
        }
    }

    /**
     * @param $url
     * @return string
     */
    public function getDownloadUrl($url)
    {
        try {
            $conn = $this->getUrlConnection($url);

            if (!$conn) {
                return null;
            }

            $conn->getInputStream()->readFully();
            return $conn->getHeaderField('X-Download-File');
        } catch (IOException $e) {
            return null;
        }
    }

    /**
     * @param $id
     * @param bool|false $absolutely
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function delete($id, $absolutely = false)
    {
        return $this->execute('project-archive/delete', [
            'id' => $id,
            'absolutely' => $absolutely
        ]);
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