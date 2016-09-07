<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AdsPosition extends Model{

    protected $table = 'ads_position';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'pos_name',
        'page_id',
        'status',
        'is_trashed',
        'quantity'

    ];

    protected $guarded = [
        'id'
    ];



    public function ads(){
        return $this->hasMany('App\Models\Ads', 'pos_id');
    }


    
    public static function getPositions($page_id){
        $list = AdsPosition::where('page_id', $page_id)
            ->where('is_trashed', 0)
            ->where('status', 1)
            ->select(['id', 'pos_name', 'width', 'height', 'is_fix'])
            ->get();

        return $list;
    }

    

}