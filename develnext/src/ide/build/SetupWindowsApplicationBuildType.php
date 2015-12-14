<?php
namespace ide\build;

use ide\forms\BuildProgressForm;
use ide\forms\BuildSuccessForm;
use ide\forms\BuildTypeConfigForm;
use ide\Ide;
use ide\project\Project;
use php\gui\UXDialog;
use php\io\File;
use php\io\MemoryStream;
use php\io\Stream;
use php\lang\Process;
use php\lib\Str;
use php\util\Regex;

/**
 * Class SetupWindowsApplicationBuildType
 * @package ide\build
 */
class SetupWindowsApplicationBuildType extends AbstractBuildType
{
    protected $windowsBuilder;

    /**
     * SetupWindowsApplicationBuildType constructor.
     */
    public function __construct()
    {
        $this->windowsBuilder = new WindowsApplicationBuildType();
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'Инсталятор Windows приложения';
    }

    /**
     * @return string
     */
    function getDescription()
    {
        return 'Установщик windows приложения все в одном файле (exe)';
    }

    /**
     * @return mixed
     */
    function getIcon()
    {
        return 'icons/setup32.png';
    }

    /**
     * @param Project $project
     *
     * @return string
     */
    function getBuildPath(Project $project)
    {
        return File::of($this->windowsBuilder->getBuildPath($project))->getParent();
    }

    /**
     * @return string
     */
    public function getConfigForm()
    {
        return 'blocks/_InnoSetupConfig.fxml';
    }

    /**
     * @return array
     */
    public function getDefaultConfig()
    {
        $project = Ide::get()->getOpenedProject();

        return [
            'name'          => $project->getName(),
            'version'       => '1.0.0',
            'linkOnDesktop' => true,
            'uuid'          => '',
        ];
    }

    function makeSetupScript($issPath, array $config)
    {
        Stream::tryAccess($issPath, function (Stream $out2) use ($config, $issPath) {
            $project = Ide::get()->getOpenedProject();

            $exeName = "{$project->getName()}.exe";
            $outputBaseFilename = "Setup";
            $outputDir = File::of($issPath)->getParent();

            $uuid = $config['uuid'] ?: Str::uuid();

            $out = new MemoryStream();

            $out->write(<<<OUT

#define MyAppName "{$config['name']}"
#define MyAppVersion "{$config['version']}"
#define MyAppPublisher ""
#define MyAppExeName "{$exeName}"

[Setup]
AppId={{{$uuid}}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
;AppVerName={#MyAppName} {#MyAppVersion}
AppPublisher={#MyAppPublisher}
DefaultDirName={pf}\\{#MyAppName}
DefaultGroupName={#MyAppName}
DisableProgramGroupPage=yes
OutputBaseFilename={$outputBaseFilename}
OutputDir={$outputDir}
Compression=lzma
SolidCompression=yes
Password={$config['password']}

[Languages]
Name: "english"; MessagesFile: "compiler:Default.isl"
Name: "russian"; MessagesFile: "compiler:Languages\\Russian.isl"
Name: "ukrainian"; MessagesFile: "compiler:Languages\\Ukrainian.isl"
Name: "spanish"; MessagesFile: "compiler:Languages\\Spanish.isl"
Name: "german"; MessagesFile: "compiler:Languages\\German.isl"
Name: "italian"; MessagesFile: "compiler:Languages\\Italian.isl"
Name: "polish"; MessagesFile: "compiler:Languages\\Polish.isl"
Name: "czech"; MessagesFile: "compiler:Languages\\Czech.isl"
Name: "japanese"; MessagesFile: "compiler:Languages\\Japanese.isl"

[Tasks]
Name: "desktopicon"; Description: "{cm:CreateDesktopIcon}"; GroupDescription: "{cm:AdditionalIcons}"; Flags: unchecked

[Files]
Source: "{$project->getName()}\\*"; DestDir: "{app}"; Flags: ignoreversion recursesubdirs createallsubdirs

[Run]
Filename: "{app}\\{#MyAppExeName}"; Description: "{cm:LaunchProgram,{#StringChange(MyAppName, '&', '&&')}}"; Flags: nowait postinstall skipifsilent

OUT
);

            $out->write("[Icons]\n");
            $out->write('Name: "{group}\\{#MyAppName}"; Filename: "{app}\\{#MyAppExeName}"' . "\n");

            if ($config['linkOnDesktop']) {
                $out->write('Name: "{commondesktop}\\{#MyAppName}"; Filename: "{app}\\{#MyAppExeName}"; Tasks: desktopicon');
            }

            if ($config['linkOnQuickLaunch']) {
                $out->write(
                    'Name: "{userappdata}\\Microsoft\\Internet Explorer\\Quick Launch\\{#MyAppName}"; Filename: "{app}\\{#MyAppExeName}"; Tasks: quicklaunchicon' . "\n"
                );
            }

            $out->seek(0);
            $out2->write(Str::encode($out->readFully(), 'windows-1251'));

        }, 'w+');
    }

    /**
     * @param Project $project
     *
     * @param bool $finished
     *
     * @return mixed
     */
    function onExecute(Project $project, $finished = true)
    {
        $config = $this->getConfig();

        if (!$config['name']) {
            UXDialog::show('Укажите название программы', 'ERROR');
            if ($this->showConfigDialog()) {
                $this->onExecute($project, $finished);
            }
            return;
        }

        /*if (!Regex::match('^[ a-zA-Z\\_\\-\\.0-9]{1,}$', $config['name'])) {
            UXDialog::show("Название программы ({$config['name']}) должно состоять только из латинских букв и спец. символов!", 'ERROR');
            if ($this->showConfigDialog()) {
                $this->onExecute($project, $finished);
            }
            return;
        }*/

        if (!$config['version']) {
            UXDialog::show('Укажите версию программы', 'ERROR');
            if ($this->showConfigDialog()) {
                $this->onExecute($project, $finished);
            }
            return;
        }

        $this->windowsBuilder->fetchConfig();
        $this->windowsBuilder->onExecute($project, function () use ($project, $finished) {
            $issPath = $this->getBuildPath($project) . '/Setup.iss';
            $this->makeSetupScript($issPath, $this->getConfig());

            $process = new Process([
                Ide::get()->getInnoSetupProgram(), '/Qp', $issPath,
            ]);

            $process = $process->start();
            $dialog = new BuildProgressForm();
            $dialog->show($process);

            $dialog->setOnExitProcess(function ($code) use ($finished, $issPath, $project) {
                $issPath = File::of($issPath);
                $issPath->delete();

                if ($code == 0) {
                    $exe = $this->getBuildPath($project) . '/Setup.exe';
                    File::of($exe)->renameTo($this->getBuildPath($project) . '/' . $project->getName() . 'Setup.exe');

                    if (is_callable($finished)) {
                        $finished();

                        return;
                    }

                    if ($finished) {
                        $dialog = new BuildSuccessForm();
                        $dialog->setBuildPath($issPath->getParent());
                        $dialog->setOpenDirectory($issPath->getParent());
                        $dialog->setRunProgram("{$issPath->getParent()}/{$project->getName()}Setup.exe");

                        $dialog->showAndWait();
                    }
                }
            });
        });
    }
}