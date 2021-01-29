<?php


namespace App\Services\TopicsService;

use App\Services\TopicsService\Contracts\TopicsManagerInterface;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;


class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
       $this->app->bind(TopicsManagerInterface::class, TopicsManager::class);
    }
}
