<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model{

    protected $table = 'admin_profile';
    public $timestamps = false;

    

    protected $fillable = [
        'admin_id',
        'dob',
        'gender',
        'cell_phone',
        'joined_date',
        'avatar'

    ];

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo('App\Models\Admin', 'admin_id');
    }
}