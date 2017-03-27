<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\forms\MessageBoxForm;
use ide\forms\OpenProjectForm;
use ide\forms\SaveProjectForLibraryForm;
use ide\Ide;
use ide\library\IdeLibraryProjectResource;
use ide\misc\AbstractCommand;
use ide\project\Project;
use php\gui\UXDialog;

class SaveProjectForLibraryCommand extends AbstractProjectCommand
{
    public function getName()
    {
        return _('menu.project.save.in.library');
    }

    public function getIcon()
    {
        return 'icons/library16.png';
    }

    public function isAlways()
    {
        return true;
    }

    /*public function getCategory()
    {
        return 'library';
    }*/

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $project = Ide::project();

        if ($project) {
            $resource = $oldResource = null;

            $oldResourcePath = $project->getIdeLibraryConfig()->get('resource');
            if (\Files::isFile($oldResourcePath)) {
                $resource = Ide::get()->getLibrary()->findResource('projects', $oldResourcePath);
                $oldResource = $resource;
            }

            $dialog = new SaveProjectForLibraryForm($resource);

            retry:
            $dialog->setResult($project);

            if ($dialog->showDialog()) {
                $result = $dialog->getResult();

                if ($oldResource) {
                    Ide::get()->getLibrary()->delete($oldResource);
                }

                /** @var IdeLibraryProjectResource $resource */
                $resource = Ide::get()->getLibrary()->makeResource('projects', $result['name'] . ".zip");

                if (!$resource) {
                    $msg = new MessageBoxForm(_('confirm.project.library.name.conflict'), ['yes' => _('btn.yes.rewrite'), 'no' => _('btn.no')]);

                    if ($msg->showDialog()) {
                        if ($msg->getResultIndex() == 1) {
                            goto retry;
                        }

                        $resource = Ide::get()->getLibrary()->makeResource('projects', $result['name'] . ".zip", true);
                    }
                }

                if ($resource) {
                    uiLater(function () use ($resource) {
                        $openDialog = new OpenProjectForm('library');
                        $openDialog->show();
                        $openDialog->selectLibraryResource($resource);
                        $openDialog->toast(_('toast.project.save.in.library.done'));
                    });

                    $project->getIdeLibraryConfig()->set('resource', $resource->getPath());

                    $resource->setName($result['name']);
                    $resource->setDescription($result['description']);
                    $project->export($resource->getPath());
                    $resource->save();
                    $project->save();

                    Ide::get()->getLibrary()->update();
                } else {
                    Ide::toast(_('toast.project.save.in.library.fail'));
                }
            }
        } else {
            UXDialog::show(_('alert.project.save.in.library.fail'));
        }
    }
}