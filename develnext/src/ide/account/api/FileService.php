<?php
namespace ide\account\api;
use action\Element;
use ide\Ide;
use ide\Logger;
use php\gui\UXNode;
use php\io\File;
use php\gui\UXImage;
use ide\utils\FileUtils;
use php\io\IOException;
use php\io\Stream;
use php\lang\ThreadPool;
use php\lib\fs;

/**
 * Class FileService
 * @package ide\account\api
 *
 * @method uploadAsync($file, callable $callback = null)
 */
class FileService extends AbstractService
{
    /**
     * @var File
     */
    protected $cachePath;

    /**
     * @var ThreadPool
     */
    protected $loadThPool;

    /**
     * MediaService constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadThPool = ThreadPool::create(1, 10, 10 * 1000);

        $this->cachePath = Ide::get()->getFile('cache/account/file');
        $this->cachePath->mkdirs();
    }

    /**
     * @param $file
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function uploadFile($file)
    {
        return parent::upload('file/upload', ['file' => $file]);
    }

    /**
     * @param $uid
     * @return ServiceResponse
     */
    public function get($uid)
    {
        return $this->executeGet("file/file/$uid");
    }

    /**
     * @param $uid
     * @return ServiceResponse
     */
    public function getUrl($uid)
    {
        $response = $this->executeGet("file/file/$uid/url");
        return $response->isSuccess() ? $response->result() : null;
    }

    /**
     * @param $uid
     * @return null|File
     */
    public function getImage($uid)
    {
        if (is_array($uid) && $uid['uid']) {
            $uid = $uid['uid'];
        }

        if (!$uid) {
            return null;
        }

        $cacheFilePath = new File($this->cachePath, "/$uid.png");

        if (!$cacheFilePath->exists()) {
            $url = $this->getUrl($uid);

            if ($url) {
                try {
                    $size = fs::copy($url, $cacheFilePath);
                    if ($size < 1) {
                        throw new IOException('Cannot write');
                    }
                    
                } catch (IOException $e) {
                    Logger::warn("Unable to load media by uid = $uid, error = {$e->getMessage()}");
                    return null;
                }
            } else {
                Logger::warn("Unable to load media by uid = $uid");
                return null;
            }
        }

        return $cacheFilePath;
    }

    public function getImageAsync($uid, callable $callback)
    {
        $fix = $uid;

        $this->loadThPool->execute(function () use ($uid, $callback) {
            $callback($this->getImage($uid));
        });
    }

    public function loadImage($uid, UXNode $node, $default = null)
    {
        if ($default) {
            $default = "res://.data/img/$default";
        }

        if (!$uid) {
            Element::loadContent($node, $default);
            return;
        }

        $this->loadThPool->execute(function () use ($uid, $node, $default) {
            $path = $this->getImage($uid);

            if ($path) {
                Element::loadContent($node, $path);
            } else {
                Element::loadContent($node, $default);
            }
        });
    }

    public function __destruct()
    {
        $this->loadThPool->shutdown();
    }
}