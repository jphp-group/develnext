<?php
namespace ide\commands\tree;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\AbstractFormFormat;
use ide\forms\ScriptHelperForm;
use ide\Ide;
use ide\project\ProjectTree;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\lib\arr;
use php\lib\fs;
use php\lib\items;
use php\lib\str;

class TreeScriptHelperMenuCommand extends AbstractMenuCommand
{
    /**
     * @var ProjectTree
     */
    private $tree;

    /**
     * ScriptHelperMenuCommand constructor.
     * @param ProjectTree $tree
     */
    public function __construct(ProjectTree $tree)
    {
        $this->tree = $tree;
    }


    public function getName()
    {
        return 'Сгенерировать скрипт';
    }

    public function getAccelerator()
    {
        return 'F3';
    }


    public function getIcon()
    {
        return 'icons/scriptHelper16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $file = $this->tree->getSelectedFullPath();
        $path = $this->tree->getSelectedPath();

        if (str::startsWith($path, '/')) {
            $path = str::sub($path, 1);
        }

        $srcPath = str::startsWith($path, 'src/') ? str::sub($path, 4) : '';
        $needPath = $srcPath ? "res://$srcPath" : $path;

        $context = 'IdeTree';

        if (fs::isDir($file)) {
            $context .= '.directory';
        } else {
            $context .= '.file';
        }

        $model = [
            'file.path' => $file,
            'file.relPath' => $path,
            'file.srcPath' => $srcPath,
            'file.needPath' => $needPath,
            'file.ext' => fs::ext($file),
            'file.name' => fs::name($file),
            'file.nameNoExt' => fs::nameNoExt($file),
            'file.pathNoExt' => fs::pathNoExt($file),
            'file.relPathNoExt' => fs::pathNoExt($path),
            'file.srcPathNoExt' => fs::pathNoExt($srcPath),
            'file.needPathNoExt' => fs::pathNoExt($needPath),

            'project.package' => Ide::project()->getPackageName(),
        ];

        $dlg = new ScriptHelperForm($context, $model);
        $dlg->showDialog();
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {

    }

    public function withBeforeSeparator()
    {
        return true;
    }


}