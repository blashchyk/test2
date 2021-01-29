<?php


namespace App\Traits;


use App\Observers\ElasticsearchObserver;

/**
 * Trait Searchable
 * @package App\Traits
 */
trait Searchable
{
    public static function bootSearchable()
    {
        if (env('ELASTICSEARCH_ENABLED')) {
            static::observe(ElasticsearchObserver::class);
        }
    }

    /**
     * @return mixed
     */
    public function getSearchIndex()
    {
        return $this->getTable();
    }

    /**
     * @return mixed
     */
    public function getSearchType()
    {
        if (property_exists($this, 'useSearchType')) {
            return $this->useSearchType;
        }
        return $this->getTable();
    }

    /**
     * @return mixed
     */
    public function toSearchArray()
    {
        return $this->toArray();
    }
}
