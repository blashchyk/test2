<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\TopicRepositoryInterface;
use App\Services\TopicsService\TopicsManager;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @var TopicRepositoryInterface
     */
    protected $topicRepository;

    /**
     * ClientController constructor.
     * @param TopicRepositoryInterface $topicRepository
     */
    public function __construct(TopicRepositoryInterface $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {

        $this->validate($request, [
            'search' => 'required',
        ]);
        $input = $request->all();
        $topics = $this->topicRepository->search($input['search'], $input['size'] ?? TopicsManager::DEFAULT_AMOUNT);
        return response()->json(['topics' => $topics]);
    }
}
