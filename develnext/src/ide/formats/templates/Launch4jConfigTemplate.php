<?php
namespace ide\formats\templates;

use ide\formats\AbstractFileTemplate;

class Launch4jConfigTemplate extends AbstractFileTemplate
{
    /**
     * @var string
     */
    protected $jrePath;

    /**
     * @var string
     */
    protected $exeName;

    /**
     * @var string
     */
    protected $icoFile;

    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            'JRE_PATH' => $this->jrePath,
            'EXE_NAME' => $this->exeName,
            'ICO_FILE' => $this->icoFile,
        ];
    }

    /**
     * @return string
     */
    public function getJrePath()
    {
        return $this->jrePath;
    }

    /**
     * @param string $jrePath
     */
    public function setJrePath($jrePath)
    {
        $this->jrePath = $jrePath;
    }

    /**
     * @return string
     */
    public function getExeName()
    {
        return $this->exeName;
    }

    /**
     * @param string $exeName
     */
    public function setExeName($exeName)
    {
        $this->exeName = $exeName;
    }

    /**
     * @return string
     */
    public function getIcoFile()
    {
        return $this->icoFile;
    }

    /**
     * @param string $icoFile
     */
    public function setIcoFile($icoFile)
    {
        $this->icoFile = $icoFile;
    }
}