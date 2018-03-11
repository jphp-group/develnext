<?php
namespace ide\formats;

use Files;
use game\SpriteSpec;
use ide\editors\AbstractEditor;
use ide\editors\GameSpriteEditor;
use ide\forms\InputMessageBoxForm;
use ide\Ide;
use ide\Logger;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\utils\FileUtils;
use php\lib\fs;
use php\lib\Str;
use php\util\Regex;
use php\xml\XmlProcessor;

class GameSpriteFormat extends AbstractFormat
{
    /**
     * @return string
     */
    public function getIcon()
    {
        return 'icons/picture16.png';
    }

    /**
     * @param $path
     * @return string
     */
    public function getTitle($path)
    {
        return fs::pathNoExt(parent::getTitle($path));
    }

    /**
     * @param $file
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        return new GameSpriteEditor($file);
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        return fs::isFile($file) && Str::endsWith($file, ".sprite");
    }

    public function delete($path)
    {
        parent::delete($path);

        $name = fs::pathNoExt($path);

        if (!fs::delete($name . ".png")) {
            Logger::warn("Cannot delete file '$name.png'");
        }

        fs::delete($name . ".jpeg");
        fs::delete($name . ".jpg");
        fs::delete($name . ".gif");

        if (fs::isDir($name)) {
            FileUtils::deleteDirectory($name);
        }
    }

    public function duplicate($path, $toPath)
    {
        parent::duplicate($path, $toPath);

        if ($project = Ide::project()) {
            $gui = GuiFrameworkProjectBehaviour::get();

            if ($gui) {
                $ideSpriteManager = $gui->getSpriteManager();
                $oldSpec = $ideSpriteManager->get(fs::nameNoExt($path));
                $ideSpriteManager->createSprite(fs::nameNoExt($toPath));
                $spec = $ideSpriteManager->get(fs::nameNoExt($toPath));

                foreach (['frameWidth', 'frameHeight', 'speed', 'defaultAnimation', 'metaCentred', 'metaAutoSize'] as $key) {
                    $spec->$key = $oldSpec->$key;
                }

                $spec->schemaFile = $toPath;
                $spec->file = fs::parent($oldSpec->file) . '/' . fs::nameNoExt($toPath) . '.' . fs::ext($oldSpec->file);

                $ideSpriteManager->saveSprite(fs::nameNoExt($toPath));
            } else {
                //parent::duplicate($path, $toPath);
            }
        } else {
           // parent::duplicate($path, $toPath);
        }

        $name = fs::pathNoExt($path);
        $toName = fs::pathNoExt($toPath);

        foreach (['png', 'jpeg', 'jpg', 'gif'] as $ext) {
            if (fs::isFile("$name.$ext")) {
                FileUtils::copyFile("$name.$ext", "$toName.$ext");
            }
        }

        if (fs::isDir($name)) {
            FileUtils::copyDirectory($name, $toName);
        }
    }


    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {

    }

    public function availableCreateDialog()
    {
        return true;
    }

    public function showCreateDialog($name = '')
    {
        $dialog = new InputMessageBoxForm('Создание нового спрайта', 'Введите название для нового спрайта', '* Только латинские буквы, цифры и _');
        $dialog->setResult($name);
        $dialog->setPattern(new Regex('^[a-z\\_]{1}[a-z0-9\\_]{0,60}$', 'i'), 'Данное название некорректное');

        $dialog->showDialog();
        return $dialog->getResult();
    }
}