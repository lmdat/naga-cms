<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsMeta extends Model{

    protected $table = 'news_meta';
    public $timestamps = false;


    protected $fillable = [
        'news_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'article_author',
        'article_tag',
        'article_section',
        'article_published_time',
        'article_modified_time',
        'og_title',
        'og_description',
        'og_url',
        'og_type',
        'og_image',
        'og_site_name'

    ];

    protected $guarded = ['id'];

    public function news(){
        return $this->belongsTo('App\Models\News', 'news_id');
    }

}