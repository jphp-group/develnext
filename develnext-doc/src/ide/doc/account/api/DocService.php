<?php
namespace ide\doc\account\api;

use ide\account\api\AbstractService;
use ide\account\api\ServiceException;
use ide\account\api\ServiceInvalidResponseException;
use ide\account\api\ServiceNotAvailableException;
use ide\account\api\ServiceResponse;
use ide\account\api\ServiceResponseFuture;
use ide\utils\Tree;

/**
 * Class DocService
 * @package ide\doc\account\api
 *
 * @method ServiceResponseFuture categoryTreeAsync(callable $callback = null)
 * @method ServiceResponseFuture categoryAsync($id, callable $callback = null)
 * @method ServiceResponseFuture categoriesAsync($parentCategoryId, callable $callback = null)
 * @method ServiceResponseFuture allCategoriesAsync(callable $callback = null)
 * @method ServiceResponseFuture entryAsync($id, callable $callback = null)
 * @method ServiceResponseFuture entriesAsync($categoryId, $offset, $limit, callable $callback = null)
 * @method ServiceResponseFuture allEntriesAsync($sort, $offset, $limit, callable $callback = null)
 * @method ServiceResponseFuture saveEntryAsync($data, callable $callback = null)
 * @method ServiceResponseFuture saveCategoryAsync($data, callable $callback = null)
 * @method ServiceResponseFuture deletedEntryAsync($id, callable $callback = null)
 * @method ServiceResponseFuture restoreEntryAsync($id, callable $callback = null)
 * @method ServiceResponseFuture deleteCategoryAsync($id, callable $callback = null)
 * @method ServiceResponseFuture restoreCategoryAsync($id, callable $callback = null)
 */
class DocService extends AbstractService
{
    /**
     * Return full tree of categories.
     */
    public function categoryTree()
    {
        $response = $this->allCategories();

        if ($response->isSuccess()) {
            $data = $response->data();

            return new ServiceResponse([
                'status' => $response->status(),
                'message' => $response->message(),
                'data' => new Tree($data, 'id', 'parentId')
            ]);
        } else {
            return $response;
        }
    }

    public function category($id)
    {
        return $this->execute('doc/category', ['id' => $id]);
    }

    public function categories($parentCategoryId = null)
    {
        return $this->execute('doc/categories', ['parentId' => $parentCategoryId]);
    }

    public function allCategories()
    {
        return $this->execute('doc/all-categories', []);
    }

    public function entry($id)
    {
        return $this->execute('doc/entry', ['id' => $id]);
    }

    public function entries($categoryId, $offset = 0, $limit = 40)
    {
        return $this->execute('doc/entries', ['categoryId' => $categoryId, 'offset' => $offset, 'limit' => $limit]);
    }

    public function allEntries($sort = 'UPDATED_AT', $offset = 0, $limit = 40)
    {
        return $this->execute('doc/entries', ['categoryId' => $sort, 'offset' => $offset, 'limit' => $limit]);
    }

    public function saveEntry($data)
    {
        return $this->execute('doc/save-entry', $data);
    }

    public function saveCategory($data)
    {
        return $this->execute('doc/save-category', $data);
    }

    public function deleteEntry($id)
    {
        return $this->saveEntry(['id' => $id, 'deleted' => true]);
    }

    public function restoreEntry($id)
    {
        return $this->saveEntry(['id' => $id, 'deleted' => false]);
    }

    public function deleteCategory($id)
    {
        return $this->saveCategory(['id' => $id, 'deleted' => true]);
    }

    public function restoreCategory($id)
    {
        return $this->saveCategory(['id' => $id, 'deleted' => false]);
    }
}