<?php


namespace App\Repositories\Contracts;


use Illuminate\Database\Eloquent\Collection;

interface TopicRepositoryInterface
{
    /**
     * @param array $topics
     * @return mixed
     */
    public function updateAllTopics(array $topics);

    /**
     * @param string $query
     * @param $size
     * @return Collection
     */
    public function search(string $query = '', $size) : Collection;
}

