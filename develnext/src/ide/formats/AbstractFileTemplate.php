<?php
namespace ide\formats;

use php\io\Stream;
use php\lib\Str;

/**
 * Class AbstractFileTemplate
 * @package ide\formats
 */
abstract class AbstractFileTemplate
{
    /**
     * @var string
     */
    protected $template;

    /**
     * AbstractFileTemplate constructor.
     */
    public function __construct()
    {
        $this->template = Stream::getContents('res://' . Str::replace(get_class($this), '\\', '/') . '.template');
    }

    /**
     * @return array
     */
    abstract public function getArguments();

    /**
     * @param string $oldContent
     * @param Stream $stream
     */
    public function apply($oldContent, Stream $stream)
    {
        $content = $oldContent;

        if (!$content) {
            $content = $this->template;

            foreach ($this->getArguments() as $code => $value) {
                $content = Str::replace($content, "#$code#", $value);
            }
        }

        $stream->write($content);
    }
}