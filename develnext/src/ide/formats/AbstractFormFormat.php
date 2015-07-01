<?php
namespace ide\formats;

use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\lang\Environment;
use php\lang\IllegalArgumentException;
use php\lang\Thread;
use php\lang\ThreadPool;
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
     * @var AbstractFormElementTag[]
     */
    protected $formElementTags = [];

    /**
     * @var AbstractMenuCommand[]
     */
    protected $contextCommands = [];

    public function isValid($file)
    {
        if (String::endsWith($file, '.fxml')) {
            return true;
        }

        return false;
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
     * @param $any
     *
     * @return AbstractFormElement|null
     */
    public function getFormElement($any)
    {
        foreach ($this->formElements as $element) {
            if ($element->isOrigin($any)) {
                return $element;
            }
        }

        return null;
    }

    /**
     * @param $any
     * @return mixed
     * @throws IllegalArgumentException
     */
    public function register($any)
    {
        if ($any instanceof AbstractFormElement) {
            $this->formElements[] = $any;
            FormEditor::initializeElement($any);
        } else if ($any instanceof AbstractMenuCommand) {
            $this->contextCommands[] = $any;
        } else if ($any instanceof AbstractFormElementTag) {
            $this->formElementTags[$any->getElementClass()] = $any;
        } else {
            throw new IllegalArgumentException("Cannot register $any");
        }
    }

    public function registerDone()
    {

    }
}