<?php
namespace ide\behaviour;

use ide\action\AbstractSimpleActionType;
use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\library\IdeLibraryScriptGeneratorResource;
use ide\utils\FileUtils;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXNode;
use php\io\IOException;
use php\lib\Str;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class AbstractBehaviourSpec
 * @package ide\behaviour
 */
abstract class AbstractBehaviourSpec
{
    const GROUP_MAIN = 'Главное';
    const GROUP_INPUT = 'Управление';
    const GROUP_ANIMATION = 'Анимация';
    const GROUP_EFFECT = 'Эффекты';
    const GROUP_LOGIC = 'Логика';
    const GROUP_GAME = 'Игровое';

    /**
     * @var bool
     */
    public $deletable = true;

    /**
     * AbstractBehaviourSpec constructor.
     * @param bool $deletable
     */
    public function __construct($deletable = true)
    {
        $this->deletable = $deletable;
    }


    /**
     * @return string
     */
    abstract public function getName();

    static function parseProperties($xmlFile)
    {
        $xml = new XmlProcessor();

        $document = $xml->parse(FileUtils::get($xmlFile));

        $result = [];

        /** @var DomElement $dom */
        foreach ($document->findAll('/behaviour/properties/property') as $dom) {
            $item = [
                'name' => $dom->getAttribute('name'),
                'code' => $dom->getAttribute('code'),
                'tooltip' => $dom->getAttribute('tooltip'),
                'editorFactory' => null,
            ];

            $item['tooltip'] = "[ ->{$item['code']} ] {$item['tooltip']}";

            if ($dom->hasAttribute('editor')) {
                $item['editorFactory'] = function () use ($dom) {
                    $editor = ElementPropertyEditor::getByCode($dom->getAttribute('editor'))->unserialize($dom);
                    return $editor;
                };
            } else {
                $item['editorFactory'] = function () use ($dom) {
                    return (new SimpleTextPropertyEditor())->unserialize($dom);
                };
            }

            $result[$item['code']] = $item;
        }

        $extends = $document->get('/behaviour/@extends');

        if ($extends) {
            if (!Str::contains($extends, '\\')) {
                $extends = __NAMESPACE__ . '\\spec\\' . $extends;
            }

            $file = 'res://' . Str::replace($extends, '\\', '/') . '.xml';

            foreach (self::parseProperties($file) as $code => $item) {
                if (!isset($result[$code])) {
                    $result[$code] = $item;
                }
            }
        }

        return $result;
    }

    /**
     * @return array [name, code, tooltip, editorFactory]
     */
    public function getProperties()
    {
        try {
            return self::parseProperties('res://' . Str::replace(get_class($this), '\\', '/') . '.xml');
        } catch (IOException $e) {
            return [];
        }
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return self::GROUP_MAIN;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        return $this->deletable;
    }

    /**
     * @return AbstractBehaviourSpec[]
     */
    public function getDependencies()
    {
        return [];
    }

    /**
     * @return string
     */
    abstract public function getDescription();

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'icons/plugin16.png';
    }

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @param $target
     * @return bool
     */
    public function isAllowedFor($target)
    {
        return true;
    }

    /**
     * @return AbstractBehaviour
     */
    public function createBehaviour()
    {
        $class = $this->getType();

        $behaviour = new $class();
        return $behaviour;
    }

    /**
     * @param UXNode $node
     * @param AbstractBehaviour $behaviour
     */
    public function refreshNode(UXNode $node, AbstractBehaviour $behaviour)
    {
    }

    /**
     * @param UXNode $node
     * @param AbstractBehaviour $behaviour
     */
    public function deleteNode(UXNode $node, AbstractBehaviour $behaviour)
    {
    }

    /**
     * @param UXNode $node
     * @param AbstractBehaviour $behaviour
     */
    public function deleteSelf(UXNode $node, AbstractBehaviour $behaviour)
    {
    }

    /**
     * @return IdeLibraryScriptGeneratorResource[]
     */
    public function getScriptGenerators()
    {
        return [];
    }
}