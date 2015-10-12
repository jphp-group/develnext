<?php
namespace php\gui\framework\behaviour\custom;

use php\format\ProcessorException;
use php\io\IOException;
use php\io\Stream;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class BehaviourLoader
 * @package behaviour\custom
 */
class BehaviourLoader
{
    public static function loadOne($targetId, DomElement $behaviours, BehaviourManager $manager, $newTargetId = null)
    {
        $targets = $behaviours->findAll("./target[@id='$targetId']");

        /** @var DomElement $domTarget */
        foreach ($targets as $domTarget) {
            $targetId = $domTarget->getAttribute('id');

            /** @var DomElement $domBehaviour */
            foreach ($domTarget->findAll('./behaviour') as $domBehaviour) {
                $type = $domBehaviour->getAttribute('type');

                $attributes = $domBehaviour->getAttributes();
                unset($attributes['type']);

                /** @var AbstractBehaviour $behaviour */
                $behaviour = new $type();
                $behaviour->setProperties($attributes);

                $manager->apply($newTargetId === null ? $targetId : $newTargetId, $behaviour);
            }
        }
    }

    public static function load($file, BehaviourManager $manager)
    {
        $xml = new XmlProcessor();

        try {
            $document = $xml->parse(Stream::getContents($file));

            $targets = $document->findAll('/behaviours/target');

            /** @var DomElement $domTarget */
            foreach ($targets as $domTarget) {
                $targetId = $domTarget->getAttribute('id');

                /** @var DomElement $domBehaviour */
                foreach ($domTarget->findAll('./behaviour') as $domBehaviour) {
                    $type = $domBehaviour->getAttribute('type');

                    $attributes = $domBehaviour->getAttributes();
                    unset($attributes['type']);

                    /** @var AbstractBehaviour $behaviour */
                    $behaviour = new $type();
                    $behaviour->setProperties($attributes);

                    $manager->apply($targetId, $behaviour);
                }
            }
        } catch (IOException $e) {
            ;
        }
    }
}