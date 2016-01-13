<?php
namespace php\gui;

/**
 * Class UXWebView
 * @package php\gui
 */
class UXWebView extends UXParent
{
    /**
     * @var double[]
     */
    public $minSize = [-1, -1];

    /**
     * @var double[]
     */
    public $maxSize = [-1, -1];

    /**
     * @var double
     */
    public $minWidth, $minHeight = -1;

    /**
     * @var double
     */
    public $maxWidth, $maxHeight = -1;

    /**
     * @readonly
     * @var UXWebEngine
     */
    public $engine;

    /**
     * @var bool
     */
    public $contextMenuEnabled;

    /**
     * See also ->engine->url (this is alias)
     * @var string
     */
    public $url;
}