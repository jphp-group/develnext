<?php
namespace ide\webplatform\project\behaviours\web;

use ide\misc\AbstractMetaTemplate;
use php\io\Stream;
use php\lib\str;

class WebMainUITemplate extends AbstractMetaTemplate
{
    /**
     * @var string
     */
    private $className = 'MainUI';

    /**
     * @var string
     */
    private $namespace = 'app';

    /**
     * @var string
     */
    private $path = '/';

    /**
     * @var array
     */
    private $forms = [];

    /**
     * @var string
     */
    private $notFoundForm;

    /**
     * @param Stream $out
     */
    public function render(Stream $out)
    {
        $namespace = $this->namespace ? "namespace $this->namespace;" : "";

        $registerFormsCode = [];
        foreach ($this->forms as $name => $class) {
            $_name = var_export($name, true);
            $registerFormsCode[] = "\t\t\$this->registerForm($_name, new \\$class());";
        }
        $registerFormsCode = str::join($registerFormsCode, "\r\n");

        $registerNotFoundCode = "";
        if ($this->notFoundForm) {
            $registerNotFoundCode = "\$this->registerNotFoundForm('NotFound', new \\$this->notFoundForm());";
        }

        $out->write("<?php
$namespace

use framework\\web\\AppUI;

/**
 * @path $this->path
 */
class $this->className extends AppUI
{
    public function __construct()
    {
        parent::__construct();

$registerFormsCode

        $registerNotFoundCode
    }
}
      
        ");
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return array
     */
    public function getForms(): array
    {
        return $this->forms;
    }

    /**
     * @param array $forms
     */
    public function setForms(array $forms)
    {
        $this->forms = $forms;
    }

    /**
     * @return string
     */
    public function getNotFoundForm(): ?string
    {
        return $this->notFoundForm;
    }

    /**
     * @param string $notFoundForm
     */
    public function setNotFoundForm(string $notFoundForm)
    {
        $this->notFoundForm = $notFoundForm;
    }

    /**
     * @return string
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className)
    {
        $this->className = $className;
    }
}