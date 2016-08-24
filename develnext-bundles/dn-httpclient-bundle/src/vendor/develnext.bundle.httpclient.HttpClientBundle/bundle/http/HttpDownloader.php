<?php
namespace bundle\http;

use php\framework\Logger;
use php\gui\framework\AbstractScript;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\time\Time;
use php\util\Scanner;
use php\util\SharedMap;
use php\util\SharedQueue;
use php\util\SharedStack;
use php\util\SharedValue;

class HttpDownloaderBreakException extends \Exception
{

}

/**
 * Class HttpDownloader
 * @package bundle\http
 */
class HttpDownloader extends AbstractScript
{
    const STATUS_WAIT = 'WAITING';
    const STATUS_DOWNLOADING = 'DOWNLOADING';
    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_ERROR = 'ERROR';

    const TEMP_EXT = '.$##';

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var string
     */
    public $destDirectory;

    /**
     * @var int
     */
    public $threadCount = 4;

    /**
     * @var array
     */
    public $urls = [];

    /**
     * @var bool
     */
    public $breakOnError = true;

    /**
     * @var bool
     */
    public $useTempFile = false;

    /**
     * @var bool
     */
    protected $_break = false;

    /**
     * @var bool
     */
    protected $_busy = false;

    /**
     * @var int
     */
    protected $_startTime = 0;

    /**
     * @var int
     */
    protected $_downloadedBytes = 0;

    /**
     * @var SharedMap
     */
    protected $_urlStats;

    /**
     * @var SharedStack
     */
    protected $_downloadedFiles;

    /**
     * @var SharedMap
     */
    protected $_progressFiles;

    /**
     * @var SharedStack
     */
    protected $_failedFiles;

    /**
     * @var ThreadPool
     */
    protected $_threadPool;

    /**
     * HttpDownloader constructor.
     */
    public function __construct()
    {
        $this->client = new HttpClient();
        $this->client->responseType = 'STREAM';

        $this->_downloadedFiles = new SharedStack();
        $this->_progressFiles = new SharedMap();
        $this->_failedFiles = new SharedStack();

        $this->_urlStats = new SharedMap();
    }

    protected function checkBreak()
    {
        if ($this->_break) {
            throw new HttpDownloaderBreakException();
        }
    }

    /**
     * @param string $url
     * @param string $fileName
     * @return HttpResponse
     * @throws HttpDownloaderBreakException
     */
    public function download($url, $fileName = null)
    {
        $this->checkBreak();

        $response = $this->client->get($url);

        $this->checkBreak();

        if ($response->isSuccess()) {
            $stream = $response->body();

            $contentLength = $response->contentLength();

            if ($fileName == null) {
                $fileName = fs::name($url);

                $contentDisposition = $response->header('Content-Disposition');

                foreach (str::split($contentDisposition, ';', 15) as $one) {
                    list($name, $value) = str::split(trim($one), '=', 2);

                    if ($value && $name == 'filename') {
                        if ($value[0] == '"') $value = str::sub($value, 1);
                        if ($value[str::length($value) - 1] == '"') $value = str::sub($value, 0, str::length($value) - 1);

                        $fileName = urldecode($value);
                    }

                    if ($value && $name == 'size') {
                        $contentLength = (int) $value;
                    }
                }
            }

            $fileName = $this->destDirectory ? "$this->destDirectory/$fileName" : $fileName;
            $this->_urlStats->set($url, [
                'url' => $url, 'response' => $response, 'file' => $fileName, 'size' => $contentLength, 'progress' => 0, 'status' => 'DOWNLOADING'
            ]);

            uiLater(function () use ($contentLength, $url, $fileName, $response) {
                $this->trigger('progress', ['url' => $url, 'response' => $response, 'file' => $fileName, 'max' => $contentLength, 'progress' => 0]);
            });

            try {
                fs::copy($stream, $this->useTempFile ? $fileName . self::TEMP_EXT : $fileName, function ($progress, $bytes) use ($contentLength, $url, $fileName, $response) {
                    $this->checkBreak();

                    $this->_downloadedBytes += $bytes;

                    $this->setStatValue($url, 'progress', $progress);

                    uiLater(function () use ($contentLength, $progress, $url, $fileName, $response) {
                        $this->trigger('progress', ['url' => $url, 'response' => $response, 'file' => $fileName, 'max' => $contentLength, 'progress' => $progress]);
                    });
                });
            } catch (IOException $e) {
                $this->checkBreak();
                $response->statusCode(400);

                $this->setStatValue($url, 'status', self::STATUS_ERROR);
                $this->setStatValue($url, 'error', $e->getMessage());

                $this->_urlStats->set($url, ['url' => $url, 'file' => $fileName, 'size' => $contentLength, 'progress' => 0, 'status' => 'DOWNLOADING']);

                uiLater(function () use ($url, $fileName, $response, $e) {
                    $this->trigger('errorOne', ['url' => $url, 'file' => $fileName, 'response' => $response, 'error' => $e->getMessage()]);
                });
                return $response;
            }

            $this->checkBreak();

            if ($this->useTempFile) {
                if (fs::exists($fileName)) {
                    fs::clean($fileName);
                    fs::delete($fileName);
                }

                if (!File::of($fileName . self::TEMP_EXT)->renameTo($fileName)) {
                    $this->checkBreak();

                    $response->statusCode(400);

                    $this->setStatValue($url, 'status', self::STATUS_ERROR);
                    $this->setStatValue($url, 'error', 'Cannot rename file');

                    uiLater(function () use ($url, $fileName, $response) {

                        $this->trigger('errorOne', ['url' => $url, 'file' => $fileName, 'response' => $response, 'error' => 'Cannot rename file']);
                    });
                }

                fs::delete($fileName . self::TEMP_EXT);
            }

            $this->setStatValue($url, 'status', self::STATUS_SUCCESS);

            uiLater(function () use ($url, $fileName, $response) {
                $this->trigger('successOne', ['url' => $url, 'file' => $fileName, 'response' => $response]);
            });
        } else {
            $this->checkBreak();
            uiLater(function () use ($url, $fileName, $response) {
                $this->trigger('errorOne', ['url' => $url, 'file' => $fileName, 'response' => $response]);
            });
        }

        return $response;
    }

    /**
     * @param $url
     * @param string $name
     * @param $value
     */
    protected function setStatValue($url, $name, $value)
    {
        $stat = (array) $this->_urlStats->get($url);
        $stat[$name] = $value;
        $this->_urlStats->set($url, $stat);
    }

    /**
     * Stop all downloads.
     */
    public function stop()
    {
        $this->_break = true;

        if ($this->_threadPool) {
            $this->_threadPool->shutdown();
            $this->_threadPool = null;
        }
    }

    /**
     * @return int
     */
    public function getSpeed()
    {
        $time = Time::seconds() - $this->_startTime;

        $speed = $this->_downloadedBytes / $time;

        return round($speed);
    }

    /**
     * @return float
     */
    public function getBitSpeed()
    {
        return round($this->getSpeed() / 8);
    }

    /**
     * @param callable $callback
     */
    public function stopAsync(callable $callback)
    {
        (new Thread(function () use ($callback) {
            $this->stopAndWait();

            if ($callback) {
                $callback();
            }
        }))->start();
    }

    public function stopAndWait()
    {
        $this->stop();

        while ($this->_busy) ;
    }

    public function start()
    {
        if ($this->_busy) {
            throw new \Exception("Cannot download, downloader is busy, use stopAndWait() method to stop it before start.");
        }

        new HttpResponse();
        new HttpAsyncResponse();

        $this->_startTime = Time::seconds();
        $this->_downloadedBytes = 0;

        $this->_downloadedFiles->clear();
        $this->_failedFiles->clear();
        $this->_progressFiles->clear();

        $this->_urlStats->clear();

        $this->_busy = true;
        $this->_break = false;

        $urls = $this->getAllUrls();

        $this->_threadPool = $thPool = ThreadPool::createFixed($this->threadCount);

        $countDone = new SharedValue();

        foreach ($urls as $name => $url) {
            $this->_urlStats->set($url, ['url' => $url, 'status' => self::STATUS_WAIT]);

            if ($this->_break) {
                break;
            }

            $thPool->submit(function () use ($url, $name, $urls, $countDone) {
                try {
                    $this->_progressFiles->set($url, $url);

                    $response = $this->download($url, is_string($name) ? $name : null);

                    $this->_progressFiles->remove($url);

                    if ($response->isSuccess()) {
                        $this->_downloadedFiles->push($url);
                    } else {
                        $this->_failedFiles->push($url);

                        if ($this->breakOnError) {
                            $this->stop();
                            $this->checkBreak();
                        }
                    }
                } catch (HttpDownloaderBreakException $e) {
                    ;
                }


                if ($countDone->setAndGet(function ($v) { return $v+1; }) == sizeof($urls)) {
                    uiLater(function () {
                        if ($this->_failedFiles->isEmpty()) {
                            $this->trigger('successAll');
                        }
                    });

                    uiLater(function () {
                        $this->trigger('done');
                    });

                    if ($this->_threadPool) {
                        $this->_threadPool->shutdown();
                    }

                    $this->_busy = false;
                }
            });
        }
    }

    public function free()
    {
        parent::free();

        $this->stop();
    }

    public function __destruct()
    {
        $this->stop();
    }

    /**
     * @return bool
     */
    public function isBusy()
    {
        return $this->_busy;
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function getUrlInfo($url)
    {
        return $this->_urlStats->get($url);
    }

    /**
     * @param string $url
     * @return string
     */
    public function getUrlStatus($url)
    {
        return $this->getUrlInfo($url)['status'];
    }

    /**
     * @param string $url
     * @return int
     */
    public function getUrlSize($url)
    {
        return (int) $this->getUrlInfo($url)['size'] ?: -1;
    }

    /**
     * @param int $url
     * @return float|int
     */
    public function getUrlProgress($url)
    {
        $size = $this->getUrlSize($url);
        $progress = (int) $this->getUrlInfo($url)['progress'];

        if ($progress == 0) return 0;

        return $progress / $size;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isUrlSuccess($url)
    {
        return $this->getUrlInfo($url)['status'] == self::STATUS_SUCCESS;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isUrlWaiting($url)
    {
        return $this->getUrlInfo($url)['status'] == self::STATUS_WAIT;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isUrlDownloading($url)
    {
        return $this->getUrlInfo($url)['status'] == self::STATUS_DOWNLOADING;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isUrlError($url)
    {
        return $this->getUrlInfo($url)['status'] == self::STATUS_ERROR;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isUrlDone($url)
    {
        return $this->isUrlSuccess($url) || $this->isUrlError($url);
    }


    /**
     * @return array
     */
    public function getDownloadedUrls()
    {
        return arr::toArray($this->_downloadedFiles);
    }

    /**
     * @return array
     */
    public function getFailedUrls()
    {
        return arr::toArray($this->_failedFiles);
    }

    /**
     * @return array
     */
    public function getProgressUrls()
    {
        return arr::toArray($this->_progressFiles);
    }

    /**
     * @return array
     */
    public function getAllUrls()
    {
        $urls = $this->urls;

        if (!is_array($urls)) {
            $urls = self::textToArray($urls, true);
        }

        return $urls;
    }

    /**
     * @return HttpClient
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * Load urls from file, url and other stream.
     * @param string $source
     * @param string $encoding
     */
    public function loadUrls($source, $encoding = 'UTF-8')
    {
        $this->urls = fs::get($source, $encoding);
    }

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
    }

    protected static function textToArray($text, $trimValues = false)
    {
        $scanner = new Scanner($text);
        $result = [];

        while ($scanner->hasNextLine()) {
            $line = $scanner->nextLine();

            if (str::trim($line)) {
                list($key, $value) = str::split($line, '=', 2);

                if ($value) {
                    $result[$key] = $trimValues ? str::trim($value) : $value;
                } else {
                    $result[] = $trimValues ? str::trim($key) : $key;
                }
            }
        }

        return $result;
    }
}