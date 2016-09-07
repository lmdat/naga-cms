<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsDetail extends Model{

    protected $table = 'news_detail';
    public $timestamps = false;


    protected $fillable = [
        'news_id',
        'main_content',

    ];

    protected $guarded = ['id'];

    public function news(){
        return $this->belongsTo('App\Models\News', 'news_id');
    }

}