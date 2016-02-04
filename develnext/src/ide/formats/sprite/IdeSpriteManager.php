<?php
namespace ide\formats\sprite;

use Files;
use game\SpriteSpec;
use ide\Logger;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use ide\systems\Cache;
use ide\utils\FileUtils;
use php\format\ProcessorException;
use php\game\UXSprite;
use php\gui\UXImage;
use php\io\IOException;
use php\lib\Str;
use php\xml\XmlProcessor;

/**
 * Class IdeSpriteManager
 * @package ide\formats\sprite
 */
class IdeSpriteManager
{
    const SPRITE_PATH = GuiFrameworkProjectBehaviour::GAME_DIRECTORY . "/sprites";
    /**
     * @var Project
     */
    protected $project;

    /**
     * @var SpriteSpec[]
     */
    protected $sprites = [];

    /**
     * @var XmlProcessor
     */
    protected $xml;

    /**
     * IdeSpriteManager constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->xml = new XmlProcessor();
    }

    public function getSpritePreview($name)
    {
        $image = $this->getSpriteImage($name);

        if (($spec = $this->sprites[$name]) && $image) {
            $sprite = new UXSprite();
            $sprite->frameSize = [$spec->frameWidth, $spec->frameHeight];
            $sprite->image = $image;

            if ($sprite->frameCount > 0) {
                return $sprite->getFrameImage(0);
            }
        }

        return null;
    }

    public function getSpriteImage($name)
    {
        if ($spec = $this->sprites[$name]) {
            if ($spec->file) {
                $file = $this->project->getFile("src/{$spec->file}");

                return $file->exists() ? Cache::getImage($file) : null;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * @return \game\SpriteSpec[]
     */
    public function getSprites()
    {
        return $this->sprites;
    }

    /**
     * @param string $name
     * @return SpriteSpec
     */
    public function createSprite($name)
    {
        $this->sprites[$name] = $spec = new SpriteSpec($name);
        $spec->frameWidth = $spec->frameHeight = 32;
        $spec->metaAutoSize = true;
        $spec->metaCentred = true;
        $this->saveSprite($name);

        return $this->project->getFile(self::SPRITE_PATH . "/$name.sprite");
    }

    public function saveSprite($name)
    {
        if ($spec = $this->sprites[$name]) {
            $document = $this->xml->createDocument();

            $root = $document->createElement('sprite');
            $document->appendChild($root);

            $root->setAttribute('file', $spec->file);
            $root->setAttribute('frameWidth', $spec->frameWidth);
            $root->setAttribute('frameHeight', $spec->frameHeight);
            $root->setAttribute('speed', $spec->speed);

            $file = $this->project->getFile(self::SPRITE_PATH . "/$name.sprite");

            FileUtils::put($file, $this->xml->format($document));
        }
    }

    /**
     * @param $name
     */
    public function reloadSprite($name)
    {
        try {
            $file = $this->project->getFile(self::SPRITE_PATH . "/$name.sprite");

            $document = $this->xml->parse(FileUtils::get($file));
            $sprite = $document->find('/sprite');

            $this->sprites[$name] = $spec = new SpriteSpec($name, $sprite);

            if (!$spec->file) {
                Logger::warn("Sprite with empty file: '$name'");
            }
        } catch (IOException $e) {
            Logger::exception("Cannot reload sprite '$name'", $e);
        } catch (ProcessorException $e) {
            Logger::exception("Cannot reload sprite '$name'", $e);
        }
    }

    public function saveAll()
    {
        foreach ($this->sprites as $name => $spec) {
            $this->saveSprite($name);
        }
    }

    public function reloadAll()
    {
        $this->sprites = [];

        FileUtils::scan($this->project->getFile(self::SPRITE_PATH), function ($filename) {
            if (Str::endsWith($filename, ".sprite")) {
                $name = $this->project->getAbsoluteFile($filename);
                $name = FileUtils::stripExtension($name->getRelativePath(self::SPRITE_PATH));

                $this->reloadSprite($name);
            }
        });
    }

    /**
     * @param $name
     * @return SpriteSpec
     */
    public function get($name)
    {
        return $this->sprites[$name];
    }
}