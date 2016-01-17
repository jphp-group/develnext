<?php
namespace ide\marker\target;

use ide\marker\AbstractMarker;
use php\gui\UXForm;
use php\gui\UXNode;

/**
 * @package ide\marker\target
 */
class DoneButtonMarkerTarget extends AbstractMarkerTarget
{
    /**
     * @var UXForm
     */
    protected $form;

    /**
     * SaveButtonMarkerTarget constructor.
     * @param UXForm $form
     */
    public function __construct(UXForm $form)
    {
        $this->form = $form;
    }

    /**
     * @return UXNode
     */
    function getMarkerNode()
    {
        $button = $this->form->{'saveButton'};

        if (!$button) {
            $button = $this->form->{'applyButton'};
        }

        if (!$button) {
            $button = $this->form->{'okButton'};
        }

        return $button;
    }
}