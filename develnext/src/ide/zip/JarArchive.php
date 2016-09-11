<?php
namespace ide\zip;


use php\compress\ArchiveEntry;
use php\compress\ArchiveInputStream;
use php\net\URL;

/**
 * Class JarArchive
 * @package ide\zip
 */
class JarArchive
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var ArchiveEntry[]
     */
    protected $entries = [];

    /**
     * JarArchive constructor.
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;

        $this->readEntries();
    }

    /**
     * @return \php\compress\ArchiveEntry[]
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param $name
     * @return ArchiveEntry
     */
    public function getEntry($name)
    {
        return $this->entries[$name];
    }

    /**
     * @param string $name
     * @return \php\io\Stream
     */
    public function getEntryStream($name)
    {
        $url = new URL("jar:file:///$this->file!/$name");

        $conn = $url->openConnection();
        return $conn->getInputStream();
    }

    protected function readEntries()
    {
        $archive = new ArchiveInputStream('zip', $this->file);

        try {
            $entries = [];

            while ($entry = $archive->nextEntry()) {
                $entries[$entry->getName()] = $entry;
            }

            $this->entries = $entries;
        } finally {
            $archive->close();
        }
    }
}