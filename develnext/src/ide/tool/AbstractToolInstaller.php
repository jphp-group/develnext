<?php
namespace ide\tool;
use ide\misc\EventHandlerBehaviour;

/**
 * Class AbstractToolInstaller
 * @package ide\tool
 */
abstract class AbstractToolInstaller
{
    use EventHandlerBehaviour;

    /**
     * @var AbstractTool
     */
    protected $tool;

    public function __construct(AbstractTool $tool)
    {
        $this->tool = $tool;
    }

    /**
     * @return AbstractTool
     */
    public function getTool()
    {
        return $this->tool;
    }

    /**
     * @return mixed
     */
    abstract public function run();

    /**
     * @param $status
     * @param int $progress
     */
    protected function triggerProgress($status, $progress)
    {
        $this->trigger('progress', [$status, $progress]);
    }

    /**
     * @param $message
     * @param string $type
     */
    protected function triggerMessage($message, $type = 'info')
    {
        $this->trigger('message', [$message, $type]);
    }

    /**
     * @param bool $success
     */
    protected function triggerDone($success)
    {
        $this->trigger('done', [$success]);
    }
}