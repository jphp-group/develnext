<?php
namespace ide\git\project\ui;

use git\Git;
use ide\forms\MessageBoxForm;
use ide\Ide;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXLabel;
use php\time\Time;
use php\time\Timer;

class GitStatusPane extends UXVBox
{
    /**
     * @var Timer
     */
    private $timer;

    public function __construct($nodes = [], $spacing = 0)
    {
        parent::__construct($nodes, $spacing);

        $this->init();

        $this->timer = Timer::every('10s', function () {
            uiLater(function () {
                $this->update();
            });
        });
    }

    private function init()
    {
    }

    public function dispose()
    {
        $this->timer->cancel();
    }

    public function update()
    {
        $project = Ide::project();

        $this->children->clear();

        if ($project) {
            $repo = new Git($project->getRootDir());

            if ($repo->isExists()) {
                $commits = $repo->log(['maxCount' => 1]);

                if ($commits) {
                    $author = $commits[0]['author'];
                    $time = new Time($commits[0]['commitTime']);

                    $label = new UXLabel($commits[0]['shortMessage'], Ide::get()->getImage('git/history16.png'));
                    $this->add($label);
                }
            } else {
                $button = new UXButton('Сохранить изменения', Ide::get()->getImage('git/history16.png'));
                $button->on('action', function () use ($repo) {
                    $repo->init(false);
                    $repo->add('.');
                    $repo->commit('Initial save.');

                    $this->update();
                });

                $this->add($button);
            }
        }
    }
}