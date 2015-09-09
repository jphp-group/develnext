<?php
namespace ide\utils;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use ide\misc\AbstractCommand;
use php\gui\layout\UXPane;
use php\gui\layout\UXVBox;
use php\gui\UXProgressIndicator;

/**
 * Class UiUtils
 * @package ide\utils
 */
class UiUtils
{
    private function __construct() {}

    /**
     * @param AbstractCommand[] $commands
     * @param bool $horizontal
     *
     * @return UXHBox|UXVBox
     */
    static function makeCommandPane(array $commands, $horizontal = true)
    {
        $pane = $horizontal ? new UXHBox() : new UXVBox();

        /** @var AbstractCommand $command */
        foreach ($commands as $command) {
            $ui = $command->makeUiForHead();

            if (!is_array($ui)) {
                $ui = [$ui];
            }

            foreach ($ui as $el) {
                $pane->add($el);
            }
        }

        return $pane;
    }
}