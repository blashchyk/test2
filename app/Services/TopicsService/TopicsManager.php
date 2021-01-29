<?php


namespace App\Services\TopicsService;


use App\Models\Topic;
use App\Models\TopicCategory;
use App\Repositories\Contracts\ElasticsearchRepositoryInterface;
use App\Repositories\Contracts\TopicCategoryInterface;
use App\Repositories\Contracts\TopicRepositoryInterface;
use App\Repositories\Eloquent\TopicRepository;
use App\Services\TopicsService\Contracts\TopicsManagerInterface;
use Illuminate\Support\Facades\DB;

class TopicsManager implements TopicsManagerInterface
{
    const RANGE = 'A:B';
    const CHUNK_SIZE = 5000;
    const DEFAULT_AMOUNT = 20;

    private $service;
    private $topicRepository;
    private $topicCategory;

    public function __construct(
        TopicRepositoryInterface $topicRepository,
        TopicCategoryInterface $topicCategory
    )
    {
        $this->connection();
        $this->topicRepository = $topicRepository;
        $this->topicCategory = $topicCategory;
    }

    public function parseTopicsFromGoogle()
    {
        $response = $this->service->spreadsheets_values->get(env('SPREADSHEET_ID'), self::RANGE);
        return $response->getValues();
    }

    private function connection()
    {
        $client = new \Google_Client();
        $client->setApplicationName(env('GOOGLE_APPLICATION_NAME'));
        $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
        $client->setAccessType('offline');
        $path =  base_path() . '/' . env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION');
        $client->setAuthConfig($path);
        $this->service = new \Google_Service_Sheets($client);
    }

    public function saveTopics()
    {
        $topics = $this->parseTopicsFromGoogle();
        $topicArray = [];
        $categoryArray= [];
        foreach ($topics as $key => $topic) {
            array_push($categoryArray, $topic[1]);
        }
        $categoryArray = array_values(array_unique($categoryArray));
        $categoryArray = $this->topicCategory->saveNewCategories($categoryArray);

        foreach ($topics as $topic) {
            array_push($topicArray, [
                'title' => $topic[0],
                'topic_category_id' => array_keys($categoryArray, $topic[1])[0]
            ]);
        }
        $this->topicRepository->updateAllTopics($topicArray);
    }
}
