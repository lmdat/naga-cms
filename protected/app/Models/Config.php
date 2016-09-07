<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Config extends Model{

    protected $table = 'config';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'tag_name',
        'params',

    ];

    protected $guarded = [
        'id'
    ];






}