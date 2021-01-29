<?php


namespace App\Models;


use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use Searchable;

    protected $table = 'topic';
    protected $fillable = [
        'title',
        'topic_category_id'
    ];
    public $timestamps = false;

    public function topicCategory()
    {
        return $this->hasOne(TopicCategory::class, 'foreign_key');
    }
}
