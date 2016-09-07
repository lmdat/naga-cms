<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsAutoPublish extends Model{
    protected $table = 'news_auto_publish';
    public $timestamps = false;


    protected $fillable = [
        'news_id',
        'published_at',
        'is_done'

    ];

    protected $guarded = ['id'];

    public function news(){
        return $this->belongsTo('App\Models\News', 'news_id');
    }

}