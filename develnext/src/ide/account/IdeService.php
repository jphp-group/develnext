<?php
namespace ide\account;

use ide\account\api\AbstractService;
use ide\account\api\ServiceResponse;
use ide\Ide;
use ide\utils\Json;
use php\lang\System;

/**
 * Class IdeService
 * @package ide\account
 *
 * @method ServiceResponse statusAsync($callback = null)
 * @method ServiceResponse startAsync($callback = null)
 * @method ServiceResponse shutdownAsync($callback)
 * @method ServiceResponse sendErrorAsync(\Exception $e, $callback)
 * @method ServiceResponse getLastUpdateAsync($channel, $callback)
 * @method ServiceResponse noticesAsync(callable $callback)
 */
class IdeService extends AbstractService
{
    public function status()
    {
        return $this->execute('ide/status', []);
    }

    public function start()
    {
        return $this->execute('ide/start', []);
    }

    public function shutdown()
    {
        return $this->execute('ide/shutdown', []);
    }

    public function notices()
    {
        return $this->execute('ide/notices', []);
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

        $project = Ide::get()->getOpenedProject();

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

            'projectPath' => $project ? $project->getFile('/')->getCanonicalPath() : null,
        ]);
    }
}