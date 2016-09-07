<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Ads extends Model{

    protected $table = 'ads';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'page_id',
        'pos_id',
        'ads_title',
        'ads_content',
        'is_auto_publish',
        'start_time',
        'end_time',
        'published_at',
        'status',
        'ordering',
        'is_out_of_date',
        'is_trashed',
        'created_by'

    ];

    protected $guarded = [
        'id'
    ];

    

    public function position(){
        return $this->belongsTo('App\Models\AdsPosition', 'pos_id');
    }

    public function auto_publish(){
        return $this->hasOne('App\Models\AdsAutoPublish', 'ads_id');
    }

    public function created_by(){
        return $this->belongsTo('App\Models\Admin', 'created_by');
    }

    public function modified_by(){
        return $this->belongsTo('App\Models\Admin', 'modified_by');
    }



}