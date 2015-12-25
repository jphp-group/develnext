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

/**
 * Class MediaService
 * @package ide\account\api
 */
class MediaService extends AbstractService
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

        $this->cachePath = Ide::get()->getFile('cache/account/media');
        $this->cachePath->mkdirs();
    }


    /**
     * @param $uid
     * @return ServiceResponse
     */
    public function get($uid)
    {
        return $this->execute('media/get', ['uid' => $uid]);
    }

    /**
     * @param $uid
     * @return null|File
     */
    public function getImage($uid)
    {
        $cacheFilePath = new File($this->cachePath, "/$uid");

        if (!$cacheFilePath->exists()) {
            $response = $this->get($uid);

            if ($response->isSuccess()) {
                try {
                    Stream::putContents($cacheFilePath, Stream::of($response->data()['path'])->readFully());
                } catch (IOException $e) {
                    Logger::warn("Unable to load media by uid = $uid, error = {$e->getMessage()}");
                    return null;
                }
            }
        }

        return $cacheFilePath;
    }

    public function loadImage($uid, UXNode $node, $default = null)
    {
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
}