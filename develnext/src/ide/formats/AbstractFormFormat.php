<?php
namespace ide\formats;

use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\project\Project;
use php\lang\Environment;
use php\lang\IllegalArgumentException;
use php\lang\IllegalStateException;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\arr;
use php\lib\fs;
use php\lib\reflect;
use php\lib\Str;
use php\lib\String;

/**
 * Class FormFormat
 * @package ide\formats
 */
abstract class AbstractFormFormat extends AbstractFormat
{
    /**
     * @var AbstractFormElement[]
     */
    protected $formElements = [];

    /**
     * @var AbstractFormElement[]
     */
    protected $formElementsByClass = [];

    /**
     * @var AbstractFormElementTag[]
     */
    protected $formElementTags = [];

    /**
     * @var AbstractMenuCommand[]
     */
    protected $contextCommands = [];

    public function isValid($file)
    {
        $ext = fs::ext($file);

        if ($ext != 'php') {
            return false;
        }

        if (!fs::isFile(fs::pathNoExt($file) . '.fxml')) {
            return false;
        }

        return true;
    }

    /**
     * @return form\AbstractFormElementTag[]
     */
    public function getFormElementTags()
    {
        return $this->formElementTags;
    }

    /**
     * @return form\AbstractFormElement[]
     */
    public function getFormElements()
    {
        return $this->formElements;
    }

    /**
     * @return AbstractMenuCommand[]
     */
    public function getContextCommands()
    {
        return $this->contextCommands;
    }

    /**
     * @param string|object $any class or object
     *
     * @return AbstractFormElement|null
     */
    public function getFormElement($any)
    {
        if ($element = $this->formElements[$any]) {
            return $element;
        }


        /** @var AbstractFormElement[] $candidates */
        $candidates = [];

        foreach ($this->formElements as $element) {
            if ($element->isOrigin($any)) {
                $candidates[] = $element;
            }
        }

        if (sizeof($candidates) == 1) {
            return arr::first($candidates);
        }

        if (!$candidates) {
            return null;
        }

        $anyClass = is_object($any) ? reflect::typeOf($any) : $any;

        foreach ($candidates as $candidate) {
            $class = $candidate->getElementClass();

            if ($anyClass === $class) {
                return $candidate;
            }
        }

        return arr::last($candidates);
    }

    /**
     * @param string $class
     * @return AbstractFormElement
     */
    public function getElementByClass($class)
    {
        return $this->formElementsByClass[$class];
    }

    /**
     * @param $any
     * @return mixed
     * @throws IllegalArgumentException
     */
    public function register($any)
    {
        if ($any instanceof AbstractFormElement) {
            $this->formElements[get_class($any)] = $any;

            if ($elementClass = $any->getElementClass()) {
                $this->formElementsByClass[$elementClass] = $any;
            }
            //FormEditor::initializeElement($any);
        } else if ($any instanceof AbstractMenuCommand) {
            $this->contextCommands[$any->getUniqueId()] = $any;
        } else if ($any instanceof AbstractFormElementTag) {
            $this->formElementTags[$any->getElementClass()] = $any;
        } else {
            throw new IllegalArgumentException("Cannot register $any");
        }
    }

    /**
     * @param $any
     * @throws IllegalArgumentException
     */
    public function unregister($any)
    {
        if ($any instanceof AbstractFormElement) {
            $element = $this->formElements[get_class($any)];

            if ($element) {
                if ($elementClass = $element->getElementClass()) {
                    unset($this->formElementsByClass[$elementClass]);
                }

                $element->unregister();
            }

            unset($this->formElements[get_class($any)]);
        } elseif ($any instanceof AbstractMenuCommand) {
            unset($this->contextCommands[$any->getUniqueId()]);
        } elseif ($any instanceof AbstractFormElementTag) {
            unset($this->formElementTags[$any->getElementClass()]);
        } else {
            throw new IllegalArgumentException("Cannot register $any");
        }
    }

    public function registerDone()
    {

    }
}