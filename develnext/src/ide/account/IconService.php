<?php
namespace ide\account;

use ide\account\api\AbstractService;

/**
 * Class IconService
 * @package ide\account
 */
class IconService extends AbstractService
{
    public function get($id)
    {
        return $this->execute('icon/get', [
            'id' => $id
        ]);
    }

    public function getList($query, $sizes, $page = 0, $limit = 50)
    {
        return $this->execute('icon/list', [
            'query' => $query,
            'sizes' => $sizes,
            'page'  => $page,
            'limit' => $limit
        ]);
    }
}