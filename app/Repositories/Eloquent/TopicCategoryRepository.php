<?php


namespace App\Repositories\Eloquent;


use App\Models\TopicCategory;
use App\Repositories\Contracts\TopicCategoryInterface;

class TopicCategoryRepository extends AbstractRepository implements TopicCategoryInterface
{
    protected $class = TopicCategory::class;

    /**
     * @param array $categories
     * @return array
     */
    public function saveNewCategories(array $categories) :array
    {
        $savedCategories = [];
        foreach ($categories as $value) {
            $id = $this->model->firstOrCreate(['title' => $value])->id;
            $savedCategories += [$id => $value];
        }
        return $savedCategories;
    }
}
