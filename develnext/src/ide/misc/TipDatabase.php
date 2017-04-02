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

            $lang = 'en';

            while ($scanner->hasNextLine()) {
                $line = str::trim($scanner->nextLine());

                if ($line) {
                    if ($line[0] == '[' && substr($line, -1) == ']') {
                        $lang = substr($line, 1, -1);
                        continue;
                    }

                    if ($line[0] == '~') {
                        $line = base64_decode(str::sub($line, 1));
                    }

                    $this->list[$lang][] = $line;
                }
            }
        } finally {
            $stream->close();
        }
    }

    /**
     * @param $lang
     * @return mixed
     */
    public function getRandom($lang)
    {
        $key = rand(0, sizeof($this->list[$lang]) - 1);

        return $this->list[$lang][$key];
    }

    /**
     * @param $lang
     * @return bool
     */
    public function hasLanguage($lang)
    {
        return !!$this->list[$lang];
    }

    public function getFirst($lang)
    {
        return arr::first($this->list[$lang]);
    }
}