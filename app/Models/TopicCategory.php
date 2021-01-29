<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TopicCategory extends Model
{
    protected $table = 'topic_category';
    protected $fillable = [
        'title',
    ];

    public $timestamps = false;
    public function topics()
    {
        return $this->hasMany(TopicCategory::class);
    }
}
