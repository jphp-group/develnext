<?php
namespace develnext\bundle\mail;

use ide\bundle\AbstractJarBundle;
use php\jsoup\Jsoup;

class JPHPMailBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP Mail";
    }

    /**
     * @return array
     */
    function getJarDependencies()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getUseImports()
    {
        return [];
    }
}