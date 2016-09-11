<?php
namespace ide\library;

use ide\project\Project;
use ide\systems\ProjectSystem;
use php\lib\Str;

/**
 * Class IdeLibraryProjectResource
 * @package ide\library
 */
class IdeLibraryProjectResource extends IdeLibraryResource
{
    /**
     * @return string
     */
    public function getCategory()
    {
        return 'projects';
    }
}