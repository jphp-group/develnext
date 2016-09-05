<?php
namespace ide\utils;
use ide\misc\SeparatorCommand;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use ide\misc\AbstractCommand;
use php\gui\layout\UXPane;
use php\gui\layout\UXVBox;
use php\gui\UXProgressIndicator;
use php\gui\UXSeparator;

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

        if ($pane instanceof UXHBox) {
            $pane->alignment = 'CENTER_LEFT';
        } else {
            $pane->alignment = 'CENTER';
        }

        /** @var AbstractCommand $command */
        foreach ($commands as $name => $command) {
            if ($command == '-' || $command instanceof SeparatorCommand) {
                $ui = new UXSeparator();
                $ui->orientation = 'VERTICAL';
                $ui->width = 5;
                $ui->paddingLeft = 3;
                $ui->maxSize = [20, 20];
            } else {
                if (!$command->getName() && !$command->getIcon()) {
                    continue;
                }

                $ui = $command->makeUiForHead();
            }

            if ($ui) {
                if (!is_array($ui)) {
                    if ($name) {
                        $ui->id = $name;
                    }

                    $ui = [$ui];
                }

                foreach ($ui as $el) {
                    if ($el) {
                        $pane->add($el);
                    }
                }
            }
        }

        return $pane;
    }
}