<?php
namespace ide\autocomplete\php;

use develnext\lexer\inspector\AbstractInspector;
use develnext\lexer\inspector\entry\TypeEntry;
use ide\autocomplete\AutoCompleteTypeLoader;
use php\lib\Str;

/**
 * Class PhpAutoCompleteLoader
 * @package ide\autocomplete\php
 */
class PhpAutoCompleteLoader extends AutoCompleteTypeLoader
{
    /**
     * @var PhpAnyAutoCompleteType
     */
    protected $anyType;
    protected $variableType;
    protected $thisType;
    protected $eventType;

    /**
     * @var array
     */
    protected $reflectionTypes = [];

    /**
     * @var AbstractInspector
     */
    private $inspector;

    /**
     * PhpAutoCompleteLoader constructor.
     * @param AbstractInspector $inspector
     */
    public function __construct(AbstractInspector $inspector)
    {
        $this->anyType = new PhpAnyAutoCompleteType('~any', $inspector);
        $this->variableType = new PhpAnyAutoCompleteType('~variable', $inspector);
        $this->thisType = new ThisAutoCompleteType();
        $this->eventType = new EventVariableAutoCompleteType();
        $this->inspector = $inspector;
    }

    public function load($name)
    {
        if ($name instanceof TypeEntry) {
            return new TypeAccessAutoCompleteType($name, 'dynamic', $this->inspector);
        }

        switch ($name) {
            case '~any':
                return $this->anyType;
            case '~variable':
                return $this->variableType;
            case '~this':
                return $this->thisType;
            case '~event':
                return $this->eventType;

            default:
                if (str::startsWith($name, '~any ')) {
                    return $this->anyType;
                }

                if (str::startsWith($name, '~this ')) {
                    return new ThisObjectAutoCompleteType(Str::sub($name, 6));
                }

                if (str::startsWith($name, '~static ')) {
                    $name = str::sub($name, 8);
                    $typeEntry = $this->inspector->findType($name, true);

                    return new TypeAccessAutoCompleteType($typeEntry ?: $name, 'static', $this->inspector);
                }

                if (str::startsWith($name, '~dynamic ')) {
                    $name = str::sub($name, 9);
                    $typeEntry = $this->inspector->findType($name, true);

                    return new TypeAccessAutoCompleteType($typeEntry ?: $name, 'dynamic', $this->inspector);
                }

                if (class_exists($name)) {
                    if ($type = $this->reflectionTypes[$name]) {
                        return $type;
                    }

                    return $this->reflectionTypes[$name] = new ReflectionClassAutoCompleteType($name);
                }
        }

        return null;
    }
}