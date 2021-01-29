<?php

namespace App\Providers;

use App\Repositories\Contracts\TopicCategoryInterface;
use App\Repositories\Contracts\TopicRepositoryInterface;
use App\Repositories\Eloquent\TopicCategoryRepository;
use App\Repositories\Eloquent\TopicRepository;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TopicRepositoryInterface::class, TopicRepository::class);
        $this->app->bind(TopicCategoryInterface::class, TopicCategoryRepository::class);
        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('elasticsearch.connections.default.hosts'))
                ->build();
        });
    }
}
