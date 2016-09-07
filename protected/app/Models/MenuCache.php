<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MenuCache extends Model{

    protected $table = 'menu_cache';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'menu_id',
        'json_data'

    ];

    protected $guarded = [
        'id'
    ];

    

    public function menu(){
        return $this->belongsTo('App\Models\Menu', 'menu_id');
    }

    





}