<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUploader extends Model{

    protected $table = 'file_uploader';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'news_id',
        'file_path',
        'file_name',
        'ori_name',
        'file_unique_key',
        'file_ext',
        'cdn_name',
        'root_url'

    ];

    protected $guarded = [
        'id'
    ];

    public function news(){
        return $this->belongsTo('App\Models\News');
    }
}