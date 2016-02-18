<?php
namespace ide\forms\area;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\editors\menu\ContextMenu;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\EventHandlerBehaviour;
use php\gui\framework\AbstractFormArea;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXHyperlink;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXMenuItem;
use php\gui\UXTextField;
use php\gui\UXWebEngine;
use php\gui\UXWebView;
use php\io\Stream;
use php\time\Time;
use php\time\TimeZone;

/**
 * @package ide\forms\area
 *
 * @property UXImageView $entryIcon
 * @property UXLabel $entryNameLabel
 * @property UXLabel $entryDescriptionLabel
 * @property UXWebView $entryContent
 * @property UXHyperlink $categoryLink
 * @property UXVBox $content
 */
class DocEntryPageArea extends AbstractFormArea
{
    use EventHandlerBehaviour;

    protected $entry = [];

    /**
     * @var array
     */
    protected $selectEntry;

    public function __construct()
    {
        parent::__construct();
    }

    public function setContent(array $entry = null)
    {
        $this->entry = $entry;
        $this->updateUi();
    }

    public function updateUi()
    {
        Ide::service()->media()->loadImage($this->entry['icon'], $this->entryIcon, 'icons/help32.png');

        $this->entryNameLabel->text = $this->entry['name'] ?: 'Неизвестная статья';
        $this->entryDescriptionLabel->text = $this->entry['description'] ?: 'Описание отсутствует ...';

        $this->categoryLink->text = $this->entry['category']['name'];
        $this->categoryLink->on('action', function () {
            uiLater(function () {
                $this->trigger('openCategory', [$this->entry['category']]);
            });
        });

        $endpoint = Ide::service()->getEndpoint();

        $this->entryContent->engine->loadContent('...');

        $this->showPreloader();

        $this->entryContent->engine->watchState(function (UXWebEngine $self, $old, $new) {
            if ($new == 'SUCCEEDED') {
                uiLater(function () {
                    $this->hidePreloader();
                });
            }
        });
        $this->entryContent->engine->load("{$endpoint}{$this->entry['contentUrl']}");


    }
}
