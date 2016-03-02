<?php
namespace develnext\bundle\orientdb;

use ide\bundle\AbstractJarBundle;

class JPHPOrientDbBundle extends AbstractJarBundle
{

    function getName()
    {
        return "JPHP OrientDB";
    }

    /**
     * @return array
     */
    function getJarDependencies()
    {
        return ['orientdb-core', 'jna', 'jna-platform', 'concurrentlinkedhashmap-lru', 'snappy-java', 'jphp-orientdb-ext'];
    }
}