<?php
namespace behaviour;

/**
 * Interface StreamLoadableBehaviour
 * @package behaviour
 *
 * @packages framework
 */
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