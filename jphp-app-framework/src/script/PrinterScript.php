<?php
namespace script;

use behaviour\SetTextBehaviour;
use php\desktop\Robot;
use php\framework\Logger;
use php\gui\framework\AbstractModule;
use php\gui\framework\AbstractScript;
use php\gui\framework\behaviour\PositionableBehaviour;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\printing\UXPrinter;
use php\gui\printing\UXPrinterJob;
use php\gui\UXImage;
use php\gui\UXNode;
use php\gui\UXScreen;
use php\gui\UXWindow;
use php\lib\reflect;
use php\lib\str;

/**
 * @package script
 *
 * @packages framework
 */
class PrinterScript extends AbstractScript
{
    /**
     * @var string
     */
    public $printerName;

    /**
     * @var bool
     */
    public $dialogEnabled = true;

    /**
     * @var string
     */
    public $jobName = 'Printer Job';

    /**
     * @var int
     */
    public $copies = 1;

    /**
     * --RU--
     * Цвет печати.
     *
     *      DEFAULT, COLOR, MONOCHROME
     * @var string
     */
    public $printColor = 'DEFAULT';

    /**
     * --RU--
     * Качество печати.
     *
     *      DEFAULT, DRAFT, LOW, NORMAL, HIGH
     * @var string
     */
    public $printQuality = 'DEFAULT';

    /**
     * --RU--
     * Тип печати.
     *
     *      DEFAULT, ONE_SIDED, DUPLEX, TUMBLE
     * @var string
     */
    public $printSides = 'DEFAULT';

    /**
     * @var UXPrinterJob
     */
    private $lastPrinterJob;

    /**
     * RobotScript constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
         if (!$this->isAvailable()) {
             Logger::warn("System has no any printer, printing is not available right now.");
         } else {
         }
    }

    /**
     * @return UXPrinter
     */
    public function getPrinter()
    {
        if ($this->isAvailable()) {
            if (!$this->printerName) {
                return UXPrinter::getDefault();
            }

            foreach (UXPrinter::getAll() as $printer) {
                if (str::equalsIgnoreCase($printer->name, $this->printerName)) {
                    return $printer;
                }
            }

            return null;
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return UXPrinter::getDefault() != null;
    }

    /**
     * @return UXPrinterJob
     */
    public function getLastPrinterJob()
    {
        return $this->lastPrinterJob;
    }

    /**
     * Print UI component.
     * --RU--
     * Отправить UI компонент на печать.
     *
     * @param UXNode $object
     * @return bool
     */
    public function print($object)
    {
        $printer = $this->getPrinter();

        if (!$printer) {
            if ($this->printerName) {
                Logger::error("System hasn't a printer with name '$this->printerName'");
            } else {
                Logger::warn("System has no any printer");
            }

            return false;
        }

        if ($object instanceof UXWindow) {
            $object = $object->layout;
        }

        if (!$object || !($object instanceof UXNode)) {
            Logger::error("Unable to print object (" . reflect::typeOf($object) . ')');
            return false;
        }

        $job = $printer->createPrintJob();
        $job->setSettings([
            'jobName' => $this->jobName,
            'copies' => $this->copies
        ]);

        foreach (['printColor', 'printSides', 'printQuality'] as $prop) {
            if ($this->{$prop} && $this->{$prop} != 'DEFAULT') {
                $job->setSettings([$prop => $this->{$prop}]);
            }
        }

        $this->lastPrinterJob = $job;

        if ($this->dialogEnabled) {
            $owner = $this->getOwner();

            if ($owner instanceof AbstractModule) {
                $owner = $owner->getContextForm();
            }

            if (!$owner) {
                $owner = app()->getMainForm();
            }

            if (!$owner->visible) {
                Logger::error("Unable to show print dialog, owner window is not visible");
                return false;
            }

            if (!$job->showPrintDialog($owner)) {
                return false;
            }
        }

        $success = $job->print($object);

        if ($success) {
            $job->end();
        }

        return $success;
    }
}