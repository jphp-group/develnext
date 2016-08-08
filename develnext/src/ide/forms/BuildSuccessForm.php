<?php
namespace ide\forms;

use ide\Ide;
use ide\Logger;
use php\gui\UXDesktop;
use php\gui\framework\AbstractForm;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXImageView;
use php\gui\UXTextField;
use php\io\File;
use php\io\IOException;
use php\lang\Process;
use php\lib\fs;
use php\lib\str;

/**
 * Class BuildSuccessForm
 * @package ide\forms
 *
 * @property UXImageView $icon
 * @property UXButton $runButton
 * @property UXButton $openButton
 * @property UXButton $exitButton
 * @property UXTextField $pathField
 */
class BuildSuccessForm extends AbstractIdeForm
{
    /**
     * @var callable
     */
    protected $onRun;

    /**
     * @var callable
     */
    protected $onOpenDirectory;

    /**
     * @var string
     */
    protected $buildPath;

    protected function init()
    {
        $this->icon->image = Ide::get()->getImage('icons/done32.png')->image;
    }

    /**
     * @param callable $onRun
     */
    public function setOnRun($onRun)
    {
        $this->onRun = $onRun;
    }

    /**
     * @param callable $onOpenDirectory
     */
    public function setOnOpenDirectory($onOpenDirectory)
    {
        $this->onOpenDirectory = $onOpenDirectory;
    }

    public function setRunProgram($pathToProgram)
    {
        $pathToProgram = is_array($pathToProgram) ? $pathToProgram : str::split($pathToProgram, ' ');

        $this->onRun = function () use ($pathToProgram) {
            try {
                $this->showPreloader();
                $result = (new Process($pathToProgram, $this->buildPath, Ide::get()->makeEnvironment()))->startAndWait();
                $this->hidePreloader();

                /*$exit = $result->getExitValue();

                if ($exit != 0) {
                    $error = $result->getError()->readFully();

                    if ($error) {
                        UXDialog::showAndWait($error, 'ERROR');
                    }
                } */
            } catch (IOException $e) {
                UXDialog::showAndWait('Невозможно запустить программу: ' . $e->getMessage(), 'ERROR');
            }
        };
    }

    public function setOpenDirectory($path)
    {
        $this->onOpenDirectory = function () use ($path) {
            $path = File::of($path);

            $desktop = new UXDesktop();
            $desktop->open($path);
        };
    }

    /**
     * @param string $buildPath
     */
    public function setBuildPath($buildPath)
    {
        $this->buildPath = $buildPath;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        Logger::info("Show build success: buildPath = {$this->buildPath}");

        $this->runButton->enabled = !!$this->onRun;
        $this->openButton->enabled = !!$this->onOpenDirectory;

        $this->pathField->text = File::of($this->buildPath);
    }

    /**
     * @event exitButton.action
     */
    public function doExitClick()
    {
        $this->hide();
    }

    /**
     * @event runButton.action
     */
    public function doRunClick()
    {
        call_user_func($this->onRun);
    }

    /**
     * @event openButton.action
     */
    public function doOpenClick()
    {
        call_user_func($this->onOpenDirectory);
    }
}