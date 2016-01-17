<?php
namespace ide\marker\target;

use ide\marker\AbstractMarker;
use php\gui\UXForm;
use php\gui\UXNode;

/**
 * @package ide\marker\target
 */
class CancelButtonMarkerTarget extends AbstractMarkerTarget
{
    /**
     * @var UXForm
     */
    protected $form;

    /**
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
        $button = $this->form->{'cancelButton'};

        if (!$button) {
            $button = $this->form->{'exitButton'};
        }

        if (!$button) {
            $button = $this->form->{'resetButton'};
        }

        return $button;
    }
}