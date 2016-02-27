<?php
namespace ide\project;


interface ProjectConsoleOutput
{
    function addConsoleLine($line, $color = '#333333');
}