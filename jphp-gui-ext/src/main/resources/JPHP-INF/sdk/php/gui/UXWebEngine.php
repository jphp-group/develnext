<?php
namespace php\gui;

use php\xml\DomDocument;

/**
 * Class UXWebEngine
 * @package php\gui
 */
abstract class UXWebEngine
{
    /**
     * @readonly
     * @var DomDocument
     */
    public $document;

    /**
     * @var bool
     */
    public $javaScriptEnabled;

    /**
     * @var string
     */
    public $userStyleSheetLocation;

    /**
     * @readonly
     * @var string
     */
    public $location;

    /**
     * @readonly
     * @var string
     */
    public $title;

    /**
     * @readonly
     * @var string READY, SCHEDULED, RUNNING, SUCCEEDED, CANCELLED, FAILED
     */
    public $state;

    /**
     * @param string $url
     */
    public function load($url) {}

    /**
     * @param string $content
     * @param string $contentType (optional)
     */
    public function loadContent($content, $contentType) {}

    /**
     * ...
     */
    public function reload() {}

    /**
     * @param string $script
     * @return mixed
     */
    public function executeScript($script) {}

    /**
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function callFunction($name, array $args)
    {
    }

    /**
     * @param string $name
     * @param callable $handler
     */
    public function addBridge($name, callable $handler)
    {
    }

    /**
     * @param string $state
     * @param callable $handler (UXWebEngine $self)
     */
    public function waitState($state, callable $handler)
    {
    }

    /**
     * @param string $event
     * @param callable $handler
     * @param string $group
     */
    public function on($event, callable $handler, $group = 'general')
    {
    }

    /**
     * @param string $event
     * @param string $group (optional)
     */
    public function off($event, $group)
    {
    }

    /**
     * @param string $event
     * @param UXEvent $e (optional)
     */
    public function trigger($event, UXEvent $e)
    {
    }
}