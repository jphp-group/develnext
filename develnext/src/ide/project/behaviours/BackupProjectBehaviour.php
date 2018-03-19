<?php

namespace ide\project\behaviours;

use ide\editors\ProjectEditor;
use ide\formats\ProjectFormat;
use ide\forms\InputMessageBoxForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\IdeConfiguration;
use ide\Logger;
use ide\misc\SimpleSingleCommand;
use ide\project\AbstractProjectBehaviour;
use ide\project\behaviours\backup\Backup;
use ide\project\behaviours\backup\BackupConfiguration;
use ide\project\behaviours\backup\BackupCreateMasterCommand;
use ide\project\behaviours\backup\BackupProjectControlPane;
use ide\project\behaviours\backup\BackupSettingsMenuCommand;
use ide\project\Project;
use ide\project\ProjectExporter;
use ide\project\ProjectImporter;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use php\compress\ZipException;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\time\Time;
use php\time\Timer;
use php\util\Regex;

/**
 * Class BackupProjectBehaviour
 * @package ide\project\behaviours
 */
class BackupProjectBehaviour extends AbstractProjectBehaviour
{
    /**
     * @var BackupProjectControlPane
     */
    private $controlPane;

    /**
     * @var Timer
     */
    protected $timer;

    /**
     * @var BackupConfiguration
     */
    protected $config;

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('save', [$this, 'doSave']);
        $this->project->on('export', [$this, 'doExport']);
        $this->project->on('close', [$this, 'doClose']);

        $this->project->on('execute', [$this, 'doExecute']);

        $this->config = new BackupConfiguration($this->project->getIdeFile("backup.conf"));
    }

    /**
     * @return BackupConfiguration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return \php\io\File
     */
    public function getBackupDir()
    {
        return $this->project->getIdeFile('backup');
    }

    public function doExecute()
    {
        //$this->makeDefaultMasterBackup();
    }

    public function doOpen()
    {
        $this->config->load();

        $this->timer = Timer::every('1s', [$this, 'doBackup']);

        $this->controlPane = new BackupProjectControlPane($this);

        /** @var ProjectFormat $projectFormat */
        if ($projectFormat = Ide::get()->getRegisteredFormat(ProjectFormat::class)) {
            $projectFormat->addControlPane($this->controlPane);
        }

        $this->makeMenu();

        fs::makeDir($this->getBackupDir());

        // Удаляем лишние бэкапы от предыдущей сессии.
        $newBackups = [];

        foreach ($this->getAutoBackups() as $backup) {
            if ($backup->isNew()) {
                $newBackups[] = $backup;
            }
        }

        foreach (flow($newBackups)->skip(1) as $backup) {
            $this->deleteBackup($backup);
        }

        if ($firstBackup = $newBackups[0]) {
            $firstBackup->setNew(false);
            $this->saveBackupProperties($firstBackup);
        }

        foreach (flow($this->getAutoBackups())
                     ->find(function (Backup $backup) { return !$backup->isNew(); })
                     ->skip($this->config->getAutoAmountMax()) as $backup) {
            $this->deleteBackup($backup);
        }

        if ($this->config->isAutoOpenTrigger()) {
            $this->makeAutoBackup();
        }
    }

    public function doSave()
    {
        $this->config->save();

        //$this->makeDefaultMasterBackup();
    }

    /**
     * Обновить UI backup pane, если необходимо.
     */
    public function refreshRequest()
    {
        $editor = FileSystem::getSelectedEditor();

        if ($editor instanceof ProjectEditor) {
            uiLater(function () use ($editor) {
                $editor->refresh();
            });
        }
    }

    public function doExport(ProjectExporter $exporter)
    {
        $exporter->removeDirectory($this->getBackupDir());
    }

    public function doClose()
    {
        if ($this->timer) {
            $this->timer->cancel();
        }

        if ($this->config->isAutoCloseTrigger()) {
            $this->makeAutoBackup();
        }
    }

    /**
     * @param Backup $backup
     */
    public function saveBackupProperties(Backup $backup)
    {
        $file = $backup->getFilename();

        $config = new IdeConfiguration("$file.properties");

        $properties = $backup->getProperties();

        foreach ($properties as $code => $value) {
            $config->set($code, $value);
        }

        $config->saveFile();
    }

    /**
     * Создать автоматический бэкап.
     *
     * @return Backup
     */
    public function makeAutoBackup()
    {
        ProjectSystem::saveOnlyRequired();

        $exporter = $this->project->makeExporter();

        $now = Time::now();

        $date = $now->toString('hhmmss-yyyymmdd');

        try {
            $exporter->save($file = "{$this->getBackupDir()}/auto/Backup-$date.zip");
        } catch (ZipException $e) {
            Logger::warn("Failed to create backup '$file', {$e->getMessage()}");
            return null;
        }

        $backup = new Backup();
        $backup->setName($now->toString('dd MMM, HH:mm:ss, yyyy'));
        $backup->setDescription('Auto-Backup');
        $backup->setCreatedAt(Time::millis());
        $backup->setFilename($file);
        $backup->setNew(true);
        $backup->setMaster(false);

        $this->saveBackupProperties($backup);

        $startTime = Ide::get()->getStartTime();
        $sessionBackups = [];

        foreach ($this->getAutoBackups() as $backup) {
            $createdAt = new Time($backup->getCreatedAt());

            if ($createdAt > $startTime) {
                $sessionBackups[] = $backup;
            }
        }

        $needDeletedBackups = flow($sessionBackups)->skip($this->config->getAutoAmountMaxInSession());

        foreach ($needDeletedBackups as $backup) {
            $this->deleteBackup($backup);
        }

        $this->project->trigger('backup', $backup);

        return $backup;
    }

    public function makeDefaultMasterBackup()
    {
        if ($name = $this->config->getMasterDefault()) {
            $time = Time::millis();
            Logger::info("Make default master backup, name = $name");

            Ide::async(function () use ($name, $time) {
                $oldBackup = $this->getMasterBackup($name);

                if ($oldBackup) {
                    $this->deleteBackup($oldBackup);
                }

                $this->makeMasterBackup($name, 'Default Master Backup');

                Logger::info("Make default master backup, name = $name, is done, time = " . (Time::millis() - $time));
            });
            //$this->refreshRequest();
        }
    }

    /**
     * Создать мастер-копию.
     *
     * @param $name
     * @param $description
     * @return Backup
     */
    public function makeMasterBackup($name, $description)
    {
        ProjectSystem::saveOnlyRequired();

        $exporter = $this->project->makeExporter();
        $now = Time::now();
        $date = $now->toString('HHmmss-yyyyMMdd');
        $exporter->save($file = "{$this->getBackupDir()}/master/Backup-$date.zip");

        $config = new IdeConfiguration("$file.properties");
        $config->set('name', $name);
        $config->set('description', $description);
        $config->set('createdAt', Time::millis());
        $config->saveFile();

        $backup = new Backup();
        $backup->setName($name);
        $backup->setDescription($description);
        $backup->setCreatedAt(Time::millis());
        $backup->setFilename($file);
        $backup->setNew(false);
        $backup->setMaster(true);

        $this->saveBackupProperties($backup);

        $this->project->trigger('backup', $backup);

        return $backup;
    }

    public function makeMasterBackupRequest()
    {
        $input = new InputMessageBoxForm('Создание мастер-копии', 'Введите название копии:');
        $input->setPattern(Regex::of('.+'), 'Введите название');

        retry:
        if ($input->showDialog()) {
            $name = $input->getResult();

            $oldBackup = $this->getMasterBackup($name);

            if ($oldBackup) {
                if (!MessageBoxForm::confirm("Мастер-копия '$name' уже существует, хотите перезаписать её?")) {
                    goto retry;
                } else {
                    $this->deleteBackup($oldBackup);
                }
            }

            $this->makeMasterBackup($name, '');

            if (!$oldBackup) {
                Ide::toast("Резервная мастер-копия ($name) успешно создана.");
            } else {
                Ide::toast("Резервная мастер-копия ($name) успешно обновлена.");
            }

            $this->refreshRequest();
        }
    }

    public function makeAutoBackupRequest()
    {
        $backup = $this->makeAutoBackup();
        Ide::toast('Резервная копия (' . $backup->getName() . ') успешно создана.');
        $this->refreshRequest();
    }

    /**
     * Удалить все бэкапы.
     * @param string $sub
     */
    public function clearBackup($sub = '')
    {
        fs::clean("{$this->getBackupDir()}/$sub");

        $this->project->trigger('clearBackup', $sub);
    }

    /**
     */
    public function clearMasterBackupRequest()
    {
        if (MessageBoxForm::confirmDelete('мастер-копии')) {
            $this->clearBackup('master');
            Ide::toast('Все мастер-копии были удалены.');

            $this->refreshRequest();
        }
    }

    /**
     */
    public function clearAutoBackupRequest()
    {
        if (MessageBoxForm::confirmDelete('автоматические копии')) {
            $this->clearBackup('auto');
            Ide::toast('Все автоматические копии были удалены.');

            $this->refreshRequest();
        }
    }

    /**
     * @param Backup $backup
     */
    public function deleteBackup(Backup $backup)
    {
        Logger::info("Delete backup $backup");

        fs::delete($backup->getFilename());
        fs::delete("{$backup->getFilename()}.properties");
    }

    /**
     * @param Backup $backup
     */
    public function deleteBackupRequest(Backup $backup)
    {
        if (MessageBoxForm::confirmDelete("копию " . $backup->getName())) {
            $this->deleteBackup($backup);

            Ide::toast("Резервная копия '{$backup->getName()}' успешно удалена.");

            $this->refreshRequest();
        }
    }

    /**
     * Возвращает список автоматических бэкапов.
     * @return Backup[]
     */
    public function getAutoBackups()
    {
        $files = fs::scan("{$this->getBackupDir()}/auto/", [
            'namePattern' => '^Backup\\-.+', 'extensions' => 'zip', 'excludeDirs' => true
        ], 1);

        $result = [];

        foreach ($files as $file) {
            if (fs::isFile("$file.properties")) {
                $config = new IdeConfiguration("$file.properties");

                $backup = new Backup($config->toArray());
                $backup->setFilename($file);

                $result[] = $backup;
            }
        }

        $result = arr::sort($result, function (Backup $a, Backup $b) {
            return $a->getCreatedAt() > $b->getCreatedAt() ? -1 : 1;
        });

        return $result;
    }

    /**
     * @param $name
     * @return Backup|null
     */
    public function getMasterBackup($name)
    {
        foreach ($this->getMasterBackups() as $backup) {
            if (str::equalsIgnoreCase(str::trim($backup->getName()), str::trim($name))) {
                return $backup;
            }
        }

        return null;
    }

    /**
     * Возвращает список мастер-копий.
     *
     * @return Backup[]
     */
    public function getMasterBackups()
    {
        $files = fs::scan("{$this->getBackupDir()}/master/", [
            'namePattern' => '^Backup\\-.+', 'extensions' => 'zip', 'excludeDirs' => true
        ], 1);

        $result = [];

        foreach ($files as $file) {
            if (fs::isFile("$file.properties")) {
                $config = new IdeConfiguration("$file.properties");
                $backup = new Backup($config->toArray());
                $backup->setFilename($file);
                $backup->setMaster(true);

                $result[] = $backup;
            }
        }

        $result = arr::sort($result, function (Backup $a, Backup $b) {
            return $a->getCreatedAt() > $b->getCreatedAt() ? -1 : 1;
        });

        return $result;
    }

    /**
     * Восстановить проект из бэкапа.
     *
     * @param Backup $backup
     * @return Project|null
     */
    public function restoreFromBackup(Backup $backup)
    {
        $oldProperties = $this->config->getProperties();

        ProjectSystem::close(false);

        $importer = new ProjectImporter($backup->getFilename());
        $importer->extract($this->project->getRootDir());

        $file = fs::scan($this->project->getRootDir(), ['extensions' => ['dnproject']])[0];

        $project = ProjectSystem::open($file, true, true, false);

        if ($project) {
            // сохраняем старые настроки.
            /** @var BackupProjectBehaviour $newBehaviour */
            if ($newBehaviour = $project->getBehaviour(BackupProjectBehaviour::class)) {
                $newBehaviour->getConfig()->setProperties($oldProperties);
                $newBehaviour->getConfig()->save();
            }
        }

        return $project;
    }

    /**
     * Запрос на восстановление бэкапа.
     *
     * @param Backup $backup
     */
    public function restoreFromBackupRequest(Backup $backup)
    {
        if (MessageBoxForm::confirm('Вы уврены, что хотите восстановить выбранный бэкап?')) {
            Ide::get()->getMainForm()->showPreloader('Подождите ...');

            waitAsync(500, function () use ($backup) {
                $project = $this->restoreFromBackup($backup);

                if ($project) {
                    Ide::toast("Проект был успешно восстановлен из бэкапа - {$backup->getName()}");

                    uiLater(function () use ($project) {
                        /** @var ProjectEditor $projectEditor */
                        $projectEditor = FileSystem::open($project->getMainProjectFile());
                        $projectEditor->navigate(BackupProjectControlPane::class);

                        Ide::get()->getMainForm()->hidePreloader();
                    });
                }
            });
        }
    }

    public function doBackup()
    {
        static $lastStamp = 0;

        if ($lastStamp == 0) {
            $lastStamp = Time::millis();
            return;
        }

        // Если IDE не активна, не делаем бэкап.
        if (Ide::get()->isIdle() || !$this->config->isAutoIntervalTrigger()) {
            return;
        }

        $diff = Time::millis() - $lastStamp;

        if ($diff > $this->config->getAutoIntervalTriggerTime()) {
            $this->makeAutoBackup();

            $lastStamp = Time::millis();

            $this->refreshRequest();
        }
    }

    public function makeMenu()
    {
        Ide::get()->getMainForm()->defineMenuGroup('backup', 'Архив проекта');

        Ide::get()->registerCommand(new BackupCreateMasterCommand($this));
        $command = SimpleSingleCommand::makeForMenu('Список копий проекта', null, function () {
            /** @var ProjectEditor $projectEditor */
            if ($projectEditor = FileSystem::open($this->project->getMainProjectFile())) {
                $projectEditor->navigate(BackupProjectControlPane::class);
            }
        });
        $command->setCategory('backup');
        Ide::get()->registerCommand($command);
        Ide::get()->registerCommand(new BackupSettingsMenuCommand($this));
        //Ide::get()->registerCommand(new BackupCleanMasterCommand($this));
    }

    /**
     * see PRIORITY_* constants
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_COMPONENT;
    }
}