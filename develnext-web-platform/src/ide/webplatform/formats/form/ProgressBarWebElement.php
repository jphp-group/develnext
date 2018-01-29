<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UIProgressBar;
use php\gui\UXNode;
use php\gui\UXProgressBar;

/**
 * Class ProgressBarWebElement
 * @package ide\webplatform\formats\form
 */
class ProgressBarWebElement extends AbstractWebElement
{
    public function getName()
    {
        return 'Прогресс';
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'ProgressBar';
    }
    public function uiStylesheets(): array
    {
        return [
            '/ide/webplatform/formats/form/ProgressBarWebElement.css'
        ];
    }

    public function getElementClass()
    {
        return UIProgressBar::class;
    }

    public function getIcon()
    {
        return 'icons/progressbar16.png';
    }

    public function getIdPattern()
    {
        return "progressBar%s";
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXProgressBar $view */
        parent::loadUiSchema($view, $uiSchema);

        if (isset($uiSchema['value'])) {
            $view->progress = $uiSchema['value'];
        }
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXProgressBar $view */
        $schema = parent::uiSchema($view);
        $schema['value'] = $view->progress;

        return $schema;
    }


    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $bar = new UXProgressBar();
        $bar->classes->addAll(['ux-progress-bar']);
        $bar->progress = 0;
        $bar->observer('progress')->addListener(function ($_, $new) use ($bar) {
            if ($new < 0) {
                $bar->progress = 0;
            }
        });

        return $bar;
    }
}