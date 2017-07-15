<?php
namespace ide\forms;

use behaviour\custom\EscapeShutdownBehaviour;
use ide\editors\CodeEditor;
use ide\forms\mixins\DialogFormMixin;
use ide\misc\AbstractCommand;
use ide\ui\Notifications;
use ide\utils\UiUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXVBox;
use php\gui\UXClipboard;
use php\gui\UXForm;
use php\gui\UXLabel;
use php\lib\str;

/**
 * Class SourceCodePreviewForm
 * @package ide\forms
 */
class SourceCodePreviewForm extends UXForm
{
    use DialogFormMixin;

    /**
     * @var CodeEditor
     */
    protected $editor;

    /**
     * SourceCodePreviewForm constructor.
     * @param string $source
     * @param string $syntax
     */
    public function __construct($source, $syntax)
    {
        parent::__construct(null);

        $this->addStylesheet('/php/gui/framework/style.css');

        new EscapeShutdownBehaviour($this);

        $this->style = 'UTILITY';
        $this->modality = 'APPLICATION_MODAL';

        $this->editor = new CodeEditor(null, $syntax, ['']);
        $this->editor->setEmbedded(true);
        //$this->editor->setReadOnly(true);
        $this->editor->setValue($source);

        $node = $this->editor->makeUi();
        UXVBox::setVgrow($node, 'ALWAYS');

        $commands = [
            AbstractCommand::makeWithText('Скопировать и закрыть', 'icons/copy16.png', function () {
                UXClipboard::setText($this->editor->getValue());
                $this->hide();
                Notifications::success('Код скопирован', 'Исходный код скопирован в буфер обмена.');
                $this->setResult(true);
            }),
            AbstractCommand::makeWithText('Скопировать', null, function () {
                UXClipboard::setText($this->editor->getValue());
                $this->hide();
                Notifications::success('Код скопирован', 'Исходный код скопирован в буфер обмена.');
            }),
            AbstractCommand::makeWithText('Закрыть', null, function () {
                $this->hide();
            })
        ];

        $commandPane = UiUtils::makeCommandPane($commands);
        $commandPane->spacing = 5;
        $commandPane->minHeight = 32;

        foreach ($commandPane->children as $i => $btn) {
            if ($i == 0) $btn->font->bold = true;

            $btn->paddingLeft = $btn->paddingRight = 15;
        }

        $box = new UXVBox([$node, $commandPane], 10);
        $box->padding = 10;
        UXAnchorPane::setAnchor($box, 0);

        $this->layout->add($box);

        $lines = str::lines($source);

        $this->minWidth = 600;
        $this->minHeight = 200;
        $this->height = min(600, sizeof($lines) * 20 + 90);
        $this->title = 'Исходный код, скрипт (' . str::upper($syntax) . ')';
    }
}