<?php
namespace ide\utils;

class Tree
{
    protected $tree = [];
    protected $list = [];

    public function __construct(array $plainTree, $idProperty = 'id', $parentProperty = 'parentId')
    {
        $tree = [];

        foreach ($plainTree as $it) {
            $this->list[$it[$idProperty]] = $it;
        }

        foreach ($this->list as &$it) {
            $id = $it[$idProperty];
            $parentId = $it[$parentProperty];

            if ($parentId) {
                $this->list[$parentId]['~sub'][] =& $it;
            } else {
                $tree[$id] =& $it;
            }
        }

        $this->tree = $tree;
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

        $sub = (array) $this->list[$parentId]['~sub'];

        /*foreach ($sub as &$one) {
            $one = $this->list[$one];
        }
               */
        return $sub;
    }

    public function getRoot()
    {
        return (array) $this->tree;
    }
}