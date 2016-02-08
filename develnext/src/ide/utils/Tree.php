<?php
namespace ide\utils;

class Tree
{
    protected $tree = [];
    protected $list = [];

    public function __construct(array $plainTree, $idProperty = 'id', $parentProperty = 'parentId')
    {
        $tree = [];
        $list = [];

        foreach ($plainTree as $it) {
            $id = $it[$idProperty];
            $parentId = $it[$parentProperty];

            if ($parentId) {
                if (!$list[$parentId])  {
                    $list[$parentId] = [];
                }

                $parent =& $list[$parentId];

                if ($list[$id]) {
                    $sub = $list[$id];
                    $list[$id] = $it;
                    $list[$id]['~sub'] = $sub;
                } else {
                    $list[$id] = $it;
                }

                $parent['~sub'][] =& $list[$id];
            } else {
                $tree[$id] = $it;
                $list[$id] =& $tree[$id];
            }
        }

        $this->tree = $tree;
        $this->list = $list;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    public function getSub($parentId)
    {
        if ($parentId === null) {
            return $this->getRoot();
        }

        return (array) $this->list[$parentId]['~sub'];
    }

    public function getRoot()
    {
        return (array) $this->tree;
    }
}