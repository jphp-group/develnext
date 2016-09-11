<?php
namespace ide\marker\target;

use ide\editors\FormEditor;
use ide\marker\AbstractMarker;
use ide\systems\FileSystem;
use php\gui\UXNode;

class CurrentEditorMarkerTarget extends AbstractMarkerTarget
{
    /**
     * @return UXNode
     */
    function getMarkerNode()
    {
        $editor = FileSystem::getSelectedEditor();

        if ($editor instanceof MarkerTargable) {
            return $editor->getMarkerNode();
        }

        return null;
    }
}