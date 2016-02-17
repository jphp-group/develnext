<?php
namespace ide\doc\editors;

use ide\account\api\ServiceResponse;
use ide\doc\account\api\DocService;
use ide\editors\AbstractEditor;
use ide\editors\common\CodeTextArea;
use ide\ui\Notifications;
use php\gui\UXNode;
use php\util\Regex;

class DocEntryEditor extends AbstractEditor
{
    /**
     * @var DocService
     */
    protected $docService;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $entry = null;

    /**
     * @var CodeTextArea
     */
    protected $uiTextArea;

    /**
     * DocEditor constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        parent::__construct($file);

        $this->docService = new DocService();

        $regex = Regex::of('^\\~doc\\/edit\\/([0-9]+)')->with($file);

        if ($regex->find()) {
            $this->id = $regex->group(1);
        }
    }

    public function updateUi()
    {
        $this->uiTextArea->text = $this->entry['content'];
    }

    public function load()
    {
        $this->docService->entryAsync($this->id, function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                $this->entry = $response->data();
                $this->updateUi();
            } else {
                Notifications::error('Îøèáêà', $response->message());
            }
        });
    }

    public function save()
    {
        if ($this->entry) {
            $this->docService->saveEntryAsync($this->entry, function (ServiceResponse $response) {
                if ($response->isSuccess()) {
                    $this->entry = $response->data();
                    $this->updateUi();
                } else {
                    Notifications::error('Îøèáêà', $response->message());
                }
            });
        }
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $area = new CodeTextArea('markdown');
        $this->uiTextArea = $area;
        return $area;
    }
}