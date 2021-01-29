<?php


namespace App\Console\Commands;


use App\Services\TopicsService\Contracts\TopicsManagerInterface;
use Illuminate\Console\Command;

class ParsingTopics extends Command
{
    protected $signature = 'parse_topics';

    public function handle(TopicsManagerInterface $topicsManager)
    {
        $topicsManager->saveTopics();
    }
}
