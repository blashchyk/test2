<?php


namespace App\Repositories\Contracts;


interface TopicCategoryInterface
{
    /**
     * @param array $categories
     * @return array
     */
    public function saveNewCategories(array $categories) :array;
}
