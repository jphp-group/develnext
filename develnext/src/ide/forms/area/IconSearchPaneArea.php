<?php
namespace ide\forms\area;

use Async;
use ide\account\api\ServiceResponse;
use ide\forms\SelectIconSizeForm;
use ide\Ide;
use ide\misc\EventHandlerBehaviour;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
use ide\ui\Notifications;
use php\gui\event\UXEvent;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractFormArea;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXApplication;
use php\gui\UXComboBox;
use php\gui\UXImage;
use php\gui\UXTextField;
use php\io\Stream;
use php\lib\str;
use php\util\SharedStack;
use script\TimerScript;
use timer\AccurateTimer;

/**
 * Class IconSearchPaneArea
 * @package ide\forms\area
 *
 * @property UXScrollPane $list
 * @property UXTextField $queryField
 */
class IconSearchPaneArea extends AbstractFormArea
{
    const PER_PAGE_SIZE = 20;

    use EventHandlerBehaviour;

    /**
     * @var FlowListViewDecorator
     */
    protected $listHelper;

    /**
     * @var string
     */
    static protected $query;

    /**
     * @var int
     */
    static protected $page = 0;

    /**
     * @var array
     */
    static protected $sizes = [16, 24, 32, 48, 64, 96, 128, 256, 512];

    /**
     * IconSearchPaneArea constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->listHelper = new FlowListViewDecorator($this->list->content);
        $this->listHelper->setMultipleSelection(false);
        $this->listHelper->setEmptyListText('Список пуст, попробуйте ввести другие ключевые слова.');

        foreach (static::$sizes as $size) {
            $checkbox = $this->{"size$size"};

            if ($checkbox) {
                $checkbox->selected = true;
            }
        }

        if (self::$query) {
            $this->queryField->text = self::$query;

            $this->doSearch();
        }
    }

    /**
     * @param array $data
     * @param bool $append
     */
    public function loadData(array $data, $append = false)
    {
        if (!$append) {
            $this->listHelper->clear();
        } else {
            $children = $this->listHelper->getChildren();
            $children->removeByIndex($children->count - 1);
        }

        foreach ($data as $i => $item) {
            if ($i >= self::PER_PAGE_SIZE) {
                $node = new ImageBox(72, 72);
                $node->setTitle('Еще ...');
                $node->setImage(ico('more32')->image);

                $node->on('click', function (UXMouseEvent $e) {
                    $this->search(self::$query, self::$sizes, (int) ($this->listHelper->count() / self::PER_PAGE_SIZE + 1));
                });
                $this->listHelper->add($node);
                break;
            }

            $id = $item['id'];
            $tags = $item['tags'];
            $preview = $item['preview'];

            $image = new ImageBox(72, 72);
            $image->setTitle($tags);
            $image->on('click', function (UXMouseEvent $e) use ($id) {
                if ($e->clickCount > 1) {
                    $this->select($id);
                }
            });

            Ide::service()->file()->loadImage($preview, $image);

            $this->listHelper->add($image);
        }
    }

    public function select($idIcon)
    {
        $this->showPreloader('Загрузка иконки ...');

        Ide::service()->icon()->getAsync($idIcon, function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                $data = $response->data();

                $list = new SharedStack();
                $tasks = [];

                foreach (['16', '24', '32', '48', '64', '96', '128', '256', '512'] as $size) {
                    if ($media = $data["media$size"]) {
                        $tasks[] = function ($finish) use ($media, $list, $size) {
                            $fix = $finish;
                            Ide::service()->file()->getImageAsync($media, function ($file) use ($list, $finish, $size) {
                                if ($file) {
                                    $list->push([$size, $file]);
                                }

                                UXApplication::runLater($finish);
                            });
                        };
                    }
                }

                Async::parallel($tasks, function () use ($list, $data) {
                    if ($list->isEmpty()) {
                        Notifications::error('Ошибка загрузки', 'Невозможно загрузить данную иконку, попробуйте позже.');
                    /*} else if ($list->count() == 1) {
                        $this->trigger('action', [$list->pop()[1]]);
                    */} else {
                        $dialog = new SelectIconSizeForm();
                        $dialog->setPack($data['pack'], $data['url'], $data['licence']);
                        $sizes = [];

                        foreach ($list as $el) {
                            $sizes[$el[0]] = $el[1];
                            $dialog->addSize($el[0], new UXImage(Stream::of($el[1])));
                        }

                        if ($dialog->showDialog()) {
                            $this->trigger('action', [$sizes[$dialog->getResult()]]);
                        }
                    }
                    $this->hidePreloader();
                });
            } else {
                Notifications::error('Ошибка загрузки', 'Невозможно загрузить данную иконку, попробуйте позже.');
                $this->hidePreloader();
            }
        });
    }

    public function search($query, array $sizes = [], $page = 0)
    {
        self::$query = $query;
        self::$sizes = $sizes;
        self::$page  = $page;

        if (!$query) {
            return;
        }

        $this->showPreloader('Поиск иконок ...');

        Ide::service()->icon()->getListAsync($query, str::join($sizes, ' '), $page * self::PER_PAGE_SIZE, self::PER_PAGE_SIZE + 1, function (ServiceResponse $response) use ($page) {
            if ($response->isSuccess()) {
                $data = $response->data();

                $this->loadData($data, $page > 0);
            } else {
                $this->listHelper->clear();

                Notifications::error('Поиск иконок', 'Возникла непредвиденная ошибка, возможно сервис недоступен.');
            }

            $this->hidePreloader();
        });
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return static::$query;
    }

    /**
     * @event queryField.keyUp
     * @event searchButton.action
     *
     * @event size16.mouseUp
     * @event size24.mouseUp
     * @event size32.mouseUp
     * @event size48.mouseUp
     * @event size64.mouseUp
     * @event size128.mouseUp
     * @event size256.mouseUp
     *
     * @param UXEvent $e
     */
    public function doSearch(UXEvent $e = null)
    {
        $sizes = [];

        foreach ([16, 24, 32, 48, 64, 96, 128, 256, 512] as $size) {
            $checkbox = $this->{"size$size"};

            if ($checkbox && $checkbox->selected) {
                $sizes[] = $size;
            }
        }

        if ($e instanceof UXKeyEvent || ($e && $e->sender instanceof UXComboBox)) {
            /** @var TimerScript $timer */
            static $timer;

            if ($timer) {
                $timer->stop();
            }

            $timer = AccurateTimer::executeAfter(500, function () use (&$timer, $sizes) {
                $this->search($this->queryField->text, $sizes);
            });

            return;
        }

        $this->search($this->queryField->text, $sizes);
    }
}