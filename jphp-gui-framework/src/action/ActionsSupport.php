<?php
namespace action;

use php\gui\framework\AbstractForm;

/**
 * Список необходимых методов для поддержки дефолтных методов из конструктора событий.
 *
 * Interface ActionsSupport
 * @package action
 */
interface ActionsSupport
{
    /**
     * @param string $name
     * @return AbstractForm
     */
    function form($name);

    /**
     * @return AbstractForm
     */
    function getContextForm();

    /**
     * @return mixed
     */
    function getContextFormName();

    /**
     * @param ...$args
     * @return mixed
     */
    function data(...$args);
}