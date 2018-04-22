<?php
namespace php\gui;

use php\lib\str;

class UXTabPaneWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        $draggable = $data->get('draggable');

        switch ($draggable) {
            case 'ALL':
            case 'ALL_EXCLUDE_FIRST':
            case 'ALL_EXCLUDE_LAST':
            case 'ALL_EXCLUDE_FIRST_LAST':

                $newTabs = [];

                /** @var UXTabPane $node */
                $node = $this->node;

                /** @var UXTab $tab */
                foreach ($node->tabs as $i => $tab) {
                    $newTab = new UXDraggableTab();
                    $newTab->text = $tab->text;
                    $newTab->content = $tab->content;
                    $newTab->graphic = $tab->graphic;
                    $newTab->tooltip = $tab->tooltip;
                    $newTab->detachable = false;
                    $newTab->draggable = true;

                    if (str::contains($draggable, '_FIRST')) {
                        $newTab->disableDragFirst = true;

                        if ($i == 0) {
                            $newTab->draggable = false;
                        }
                    }

                    if (str::contains($draggable, '_LAST')) {
                        $newTab->disableDragLast = true;

                        if ($i == $node->tabs->count - 1) {
                            $newTab->draggable = false;
                        }
                    }

                    $newTabs[] = $newTab;
                }

                $node->tabs->clear();
                $node->tabs->addAll($newTabs);

                break;
        }
    }
}