<?php


namespace App\Repositories\Eloquent;


use App\Models\Topic;
use App\Repositories\Contracts\TopicRepositoryInterface;
use App\Services\TopicsService\TopicsManager;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class TopicRepository extends AbstractRepository implements TopicRepositoryInterface
{
    /**
     * @var string
     */
    protected $class = Topic::class;

    /**
     * @param string $query
     * @param $size
     * @return Collection
     */
    public function search(string $query = '', $size) : Collection
    {
        $items = $this->searchOnElasticsearch($query, $size);
        return $this->buildCollection($items);
    }

    /**
     * @param string $query
     * @param int $size
     * @return array
     */
    private function searchOnElasticsearch(string $query = '', int $size): array
    {
        $elasticsearch = app()->make(Client::class);
        return $elasticsearch->search([
            'index' => $this->model->getSearchIndex(),
            'type' => $this->model->getSearchType(),
            'size' => $size,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title^5',],
                        'query' => $query
                    ],
                ],
            ],
        ]);
    }

    /**
     * @param array $items
     * @return Collection
     */
    private function buildCollection(array $items) : Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');
        return $this->model::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
    }

    /**
     * @param array $topics
     */
    public function updateAllTopics(array $topics)
    {
        $this->model::truncate();
        foreach (array_chunk($topics,TopicsManager::CHUNK_SIZE) as $value) {
            $this->model->insert($value);
        }
    }
}

