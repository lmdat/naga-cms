<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AdsAutoPublish extends Model{

    protected $table = 'ads_auto_publish';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'ads_id',
        'published_at',
        'is_done'

    ];

    protected $guarded = [
        'id'
    ];



    public function ads(){
        return $this->belongsTo('App\Models\Ads');
    }

    

}