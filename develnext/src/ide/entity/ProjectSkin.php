<?php
namespace ide\entity;

use ide\misc\AbstractEntity;
use php\compress\ZipFile;
use php\io\Stream;
use php\util\Configuration;

class Skin extends AbstractEntity
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
     * @param string $file zip file
     */
    public static function createFromFile($file)
    {
        $result = new Skin();
        
        try {
            $zip = new ZipFile($file);
            if ($zip->has('skin.properties')) {
                $zip->read('skin.properties', function (array $stat, Stream $reader) {
                    $this->setProperties((new Configuration($reader))->toArray());
                });
            }
        } catch (ZipException $e) {
            Logger::error("Unable to read zip, {$e->getMessage()}, $this->skinFile");
            $this->valid = false;
        }
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
}