<?php

namespace ide\entity;

use ide\misc\AbstractEntity;
use php\compress\ZipException;
use php\compress\ZipFile;
use php\io\IOException;
use php\io\MemoryStream;
use php\io\Stream;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\util\Configuration;

class ProjectSkin extends AbstractEntity
{
    /**
     * @var string
     */
    private $uid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $authorSite;

    /**
     * @var string
     */
    private $file;

    /**
     * @var array
     */
    private $scopes = [];

    /**
     * @return ProjectSkin
     */
    public static function createEmpty()
    {
        return new ProjectSkin();
    }

    /**
     * @param $path
     * @return ProjectSkin
     * @throws IOException
     */
    public static function createFromDir($path): ProjectSkin
    {
        $result = new ProjectSkin();
        $result->setName(fs::nameNoExt($path));
        $result->setUid(fs::normalize($path));


        if (fs::isFile("$path/skin.json")) {
            Stream::tryAccess("$path/skin.json", function (Stream $stream) use ($result) {
                $result->loadFromFile($stream);
            });
        }

        $result->setFile($path);

        return $result;
    }

    /**
     * @param string $file zip file
     * @return ProjectSkin
     * @throws ZipException
     * @throws IOException
     */
    public static function createFromZip($file): ProjectSkin
    {
        $result = new ProjectSkin();
        $result->setName(fs::nameNoExt($file));
        $result->setUid(fs::normalize($file));

        $zip = new ZipFile($file);

        if ($zip->has('skin.json')) {
            $zip->read('skin.json', function (array $stat, Stream $reader) use ($result) {
                $result->loadFromFile($reader);

                if (!$result->getDescription() && $stat['comment']) {
                    $result->setDescription($stat['comment']);
                }
            });
        } else {
            throw new IOException("skin.json not found");
        }

        $result->setFile($file);

        return $result;
    }

    /**
     * @param mixed $cssSourceFile
     * @param mixed $zipDestFile
     * @param array $additionalFiles
     * @return ZipFile
     */
    public function saveToZip($cssSourceFile, $zipDestFile, array $additionalFiles = []): ZipFile
    {
        $stream = new MemoryStream();
        $this->saveToFile($stream);
        $stream->seek(0);

        if (fs::isFile($zipDestFile)) {
            fs::delete($zipDestFile);
        }

        $zip = new ZipFile($zipDestFile, true);

        $zip->addFromString('skin.json', "$stream");
        $zip->add('skin.css', $cssSourceFile);

        foreach ($additionalFiles as $name => $srcFile) {
            $zip->add($name, $srcFile);
        }

        return $zip;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     */
    public function setUid(string $uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getAuthorSite(): string
    {
        return $this->authorSite;
    }

    /**
     * @param string $authorSite
     */
    public function setAuthorSite(string $authorSite)
    {
        $this->authorSite = $authorSite;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile(string $file)
    {
        $this->file = $file;
    }

    /**
     * @param $directory
     * @param string|null $charset
     * @param callable|null $callback
     */
    public function unpack($directory, ?string $charset = null, callable $callback = null)
    {
        $file = new ZipFile($this->file);
        $file->unpack($directory, $charset, $callback);
    }

    /**
     * @return array
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @param array|string $scopes
     */
    public function setScopes($scopes)
    {
        $this->scopes = is_array($scopes) ? $scopes : str::split($scopes, '|');
    }

    /**
     * Is Empty skin.
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->file;
    }

    /**
     * @param string[] $scopes
     * @return bool
     */
    public function hasAnyScope(string ...$scopes): bool
    {
        return flow($scopes)
            ->find(function ($scope) {
                return arr::has($this->scopes, $scope);
            });
    }
}