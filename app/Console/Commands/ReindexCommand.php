<?php


namespace App\Console\Commands;


use App\Models\Topic;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all topics to Elasticsearch';
    /**
     * @var Client
     */
    private $elasticsearch;

    /**
     * ReindexCommand constructor.
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Indexing all $topics. This might take a while...');
        foreach (Topic::cursor() as $topic)
        {
            $this->elasticsearch->index([
                'index' => $topic->getSearchIndex(),
                'type' => $topic->getSearchType(),
                'id' => $topic->getKey(),
                'body' => $topic->toSearchArray(),
            ]);
            $this->output->write('.');
        }
        $this->info('\nDone!');
    }
}
