<?php
namespace ide\misc;
use php\lib\arr;
use php\lib\str;
use php\util\Scanner;
use php\io\Stream;

/**
 * Class TipDatabase
 * @package ide\misc
 */
class TipDatabase
{
    /**
     * @var string
     */
    protected $source;

    /**
     * @var array
     */
    protected $list = [];

    /**
     * TipDatabase constructor.
     * @param string $source
     */
    public function __construct($source)
    {
        $this->source = $source;

        $stream = Stream::of($source);

        try {
            $scanner = new Scanner($stream, 'UTF-8');

            while ($scanner->hasNextLine()) {
                $line = str::trim($scanner->nextLine());

                if ($line) {
                    if ($line[0] == '~') {
                        $line = base64_decode(str::sub($line, 1));
                    }

                    $this->list[] = $line;
                }
            }
        } finally {
            $stream->close();
        }
    }

    /**
     * @return mixed
     */
    public function getRandom()
    {
        $key = rand(0, sizeof($this->list) - 1);

        return $this->list[$key];
    }

    public function getFirst()
    {
        return arr::first($this->list);
    }
}