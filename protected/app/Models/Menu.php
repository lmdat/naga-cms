<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Menu extends Model{

    protected $table = 'menu';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'menu_title',
        'menu_pos',
        'status',
        'is_trashed'

    ];

    protected $guarded = [
        'id'
    ];

    

    public function position(){
        return $this->belongsTo('App\Models\AdsPosition');
    }

    public function items(){
        return $this->hasMany('App\Models\MenuItem', 'menu_id');
    }

    public function menu_cache(){
        return $this->hasOne('App\Models\MenuCache', 'menu_id');
    }





}