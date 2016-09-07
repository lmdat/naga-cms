<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsRelation extends Model{
    protected $table = 'news_relation';
    public $timestamps = false;


    protected $fillable = [
        'news_id',
        'relation_ids'

    ];

    protected $guarded = ['id'];

    public function news(){
        return $this->belongsTo('App\Models\News', 'news_id');
    }

}