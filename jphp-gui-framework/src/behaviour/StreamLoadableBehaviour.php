<?php
namespace behaviour;

interface StreamLoadableBehaviour
{
    /**
     * @param $path
     * @return mixed
     */
    function loadContentForObject($path);

    /**
     * @param $content
     * @return mixed
     */
    function applyContentToObject($content);
}