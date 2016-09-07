<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsHitCounter extends Model{
    protected $table = 'news_hit_counter';
    public $timestamps = false;


    protected $fillable = [
        'news_id',
        'hit_counter'

    ];

    protected $guarded = ['id'];

    public function news(){
        return $this->belongsTo('App\Models\News', 'news_id');
    }

}