<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{
    
    protected $table = 'role';
    public $timestamps = false;
    
    protected $fillable = [
        'role_name',
        'alias',
        'power'
        
    ];
    
    protected $guarded = ['id'];
    
    
    public function admins(){
        return $this->belongsToMany('App\Models\Admin', 'admin_role', 'role_id', 'admin_id');
    }
    
}