<?php
namespace develnext\bundle\jsoup;

use develnext\bundle\jsoup\components\JsoupScriptComponent;
use ide\bundle\AbstractBundle;
use ide\formats\ScriptModuleFormat;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use php\lib\fs;

class JsoupBundle extends AbstractBundle
{
    function getName()
    {
        return "HTML Парсер (Jsoup)";
    }

    function getDescription()
    {
        return "Пакет для парсинга html и сайтов в стиле апи jQuery";
    }

    public function isAvailable(Project $project)
    {
        return $project->hasBehaviour(GuiFrameworkProjectBehaviour::class);
    }

    public function getDependencies()
    {
        return [
            JPHPJsoupBundle::class
        ];
    }

    public function onPreCompile(Project $project, $env, callable $log = null)
    {
        $file = $project->getSrcFile('script/JsoupScript.php', true);
        fs::ensureParent($file);

        fs::copy('res://script/JsoupScript.php', $file);
    }

    public function onAdd(Project $project)
    {
        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->register(new JsoupScriptComponent());
        }
    }

    public function onRemove(Project $project)
    {
        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->unregister(new JsoupScriptComponent());
        }
    }
}