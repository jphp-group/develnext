<?php
namespace ide\account;

use ide\account\api\AbstractService;
use ide\account\api\ServiceResponse;
use ide\Ide;
use ide\utils\Json;
use php\desktop\Runtime;
use php\gui\UXScreen;
use php\lang\System;
use php\lib\str;

/**
 * Class IdeService
 * @package ide\account
 *
 * @method ServiceResponse statusAsync($callback = null)
 * @method ServiceResponse sendErrorAsync(\Exception $e, $callback = null)
 * @method ServiceResponse getLastUpdateAsync($channel, $callback)
 */
class IdeService extends AbstractService
{
    public function status()
    {
        return $this->executeGet('ide/status');
    }

    /**
     * @param string $channel NIGHT, BETA, STABLE
     * @return ServiceResponse
     */
    public function getLastUpdate($channel = 'NIGHT')
    {
        return $this->execute('ide/get-last-update', ['channel' => $channel]);
    }

    public function sendError(\Exception $e)
    {
        $winSize = Ide::get()->getMainForm()->size;

        $ide = Ide::get();
        $project = Ide::get()->getOpenedProject();

        $data = [];

        $screen = UXScreen::getPrimary();

        $freeMemory = round(Runtime::freeMemory() / 1024 / 1024);
        $maxMemory = round(Runtime::maxMemory() / 1024 / 1024);
        $totalMemory = round(Runtime::totalMemory() / 1024 / 1024);

        $data[] = "runtime (processors=" . Runtime::availableProcessors() . ", free=$freeMemory, allocated=$totalMemory, max=$maxMemory)";
        $data[] = "screen (dpi={$screen->dpi}, size={$screen->visualBounds['width']}x{$screen->visualBounds['height']})";
        $data[] = "ide (hash={$ide->getConfig()->get('app.hash')}, idle=" . ($ide->isIdle() ? 'true' : 'false') . ", toolPath={$ide->getToolPath()})";

        if ($project) {
            $data[] = "project (name={$project->getName()}, path={$project->getFile('')})";
        }

        return $this->execute('ide/send-error', [
            'ideVersion' => Ide::get()->getName() . ' / ' . Ide::get()->getVersion(),
            'idePath'    => Ide::get()->getOwnFile('/')->getCanonicalPath(),
            'ideConfig'  => Json::encode(Ide::get()->getUserConfig('ide')->toArray()),
            'ideWindowSize' => "{$winSize[0]}x{$winSize[1]}",

            'osName' => System::getProperty('os.name'),

            'exceptionClass' => get_class($e),
            'exceptionMessage' => $e->getMessage(),
            'exceptionFile' => $e->getFile(),
            'exceptionLine' => $e->getLine(),
            'exceptionPosition' => $e->getPosition(),
            'exceptionStackTrace' => $e->getTraceAsString(),

            'data' => str::join($data, "\n"),

            'projectPath' => $project ? $project->getFile('/')->getCanonicalPath() : null,
        ]);
    }
}