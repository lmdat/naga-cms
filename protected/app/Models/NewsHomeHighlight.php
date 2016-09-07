<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsHomeHighlight extends Model{
    protected $table = 'news_home_highlight';
    public $timestamps = false;


    protected $fillable = [
        'news_id',
        'hl_intro_content',
        'hl_alias',
        'hl_start_time',
        'hl_end_time'

    ];

    protected $guarded = ['id'];

    public function news(){
        return $this->belongsTo('App\Models\News', 'news_id');
    }

}