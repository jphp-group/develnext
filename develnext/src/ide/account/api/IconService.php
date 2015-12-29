<?php
namespace ide\account\api;


/**
 * Class IconService
 * @package ide\account
 *
 * @method getListAsync($query, $sizes, $page, $limit, callable $callback = null)
 * @method getAsync($id, callable $callback = null)
 */
class IconService extends AbstractService
{
    public function get($id)
    {
        return $this->execute('icon/get', [
            'id' => $id
        ]);
    }

    public function getList($query, $sizes, $offset = 0, $limit = 50)
    {
        return $this->execute('icon/list', [
            'query' => $query,
            'sizes' => $sizes,
            'offset'  => $offset,
            'limit' => $limit
        ]);
    }
}