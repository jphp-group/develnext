<?php
namespace ide\library;

/**
 * Class IdeLibrarySnippetResource
 * @package ide\library
 */
class IdeLibrarySnippetResource extends IdeLibraryResource
{
    /**
     * @return string
     */
    public function getCategory()
    {
        return 'snippets';
    }
}