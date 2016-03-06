<?php
namespace develnext\bundle\jsoup;

use ide\bundle\AbstractJarBundle;
use php\jsoup\Jsoup;

class JPHPJsoupBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP Jsoup";
    }

    /**
     * @return array
     */
    function getJarDependencies()
    {
        return ["jphp-jsoup-ext", "jsoup"];
    }

    /**
     * @return array
     */
    public function getUseImports()
    {
        return [Jsoup::class];
    }
}