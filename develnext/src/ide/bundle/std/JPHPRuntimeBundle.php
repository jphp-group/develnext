<?php
namespace ide\bundle\std;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\project\behaviours\GradleProjectBehaviour;
use php\io\File;
use php\io\FileStream;
use php\io\IOException;
use php\io\MemoryStream;
use php\io\ResourceStream;
use php\io\Stream;
use php\lang\Environment;
use php\lang\Module;
use php\lang\Process;
use php\lang\System;
use php\lang\Thread;
use php\lang\ThreadGroup;
use php\lang\ThreadPool;
use php\lib\arr;
use php\lib\bin;
use php\lib\char;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;
use php\net\NetStream;
use php\net\Proxy;
use php\net\ServerSocket;
use php\net\Socket;
use php\net\SocketException;
use php\net\URL;
use php\time\Time;
use php\time\TimeFormat;
use php\time\TimeZone;
use php\util\Configuration;
use php\util\Flow;
use php\util\Locale;
use php\util\Regex;
use php\lib\num;

class JPHPRuntimeBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP Runtime";
    }

    function getDescription()
    {
        return "JPHP Рантайм";
    }

    /**
     * @return array
     */
    function getJarDependencies()
    {
        return ['jphp-runtime', 'dn-php-sdk', 'dn-jphp-sdk'];
    }

    /**
     * @return array
     */
    public function getUseImports()
    {
        return [
            // php\lib
            arr::class, bin::class, char::class, fs::class, str::class, num::class, reflect::class,

            // php\io
            Stream::class, File::class, IOException::class, FileStream::class, MemoryStream::class, ResourceStream::class, NetStream::class,

            // php\util
            Flow::class, Locale::class, Regex::class, Configuration::class,

            // php\time
            Time::class, TimeZone::class, TimeFormat::class,

            // php\net
            URL::class, Socket::class, SocketException::class, ServerSocket::class, Proxy::class,
            
            // php\lang
            Thread::class, Environment::class, Process::class, System::class, Thread::class, ThreadGroup::class, ThreadPool::class,
        ];
    }
}
