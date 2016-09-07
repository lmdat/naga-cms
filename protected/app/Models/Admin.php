<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
    
class Admin extends Model implements AuthenticatableContract, CanResetPasswordContract{
    
    use Authenticatable, CanResetPassword;
    
    protected $table = 'admin';
    public $timestamps = false;
    //public $incrementing = false;
    
    protected $fillable = [
        'first_name',
        'surname',
        'email',
        'password',
        'salt',
        'active',
        'created_by',
        'role_id'
        
    ];
    
    protected $guarded = [
        'id'
    ];
    
    protected $hidden = ['password', 'remember_token'];
    
    
    /*
    public function tasks(){
        return $this->hasMany('App\Models\Task', 'user_id');
    }
    
    public function token(){
        return $this->hasOne('App\Models\Token', 'user_id');
    }
    */

    public function news_create_list(){
        return $this->hasMany('App\Models\News', 'created_by');
    }

    public function news_modify_list(){
        return $this->hasMany('App\Models\News', 'modified_by');
    }

    public function ads_create_list(){
        return $this->hasMany('App\Models\Ads', 'created_by');
    }

    public function ads_modify_list(){
        return $this->hasMany('App\Models\Ads', 'modified_by');
    }

    public function profile(){
        return $this->hasOne('App\Models\AdminProfile', 'admin_id');
    }
    
    public function roles(){

        return $this->belongsToMany('App\Models\Role', 'admin_role', 'admin_id', 'role_id');
    }

    public function maxRole(){
        return $this->roles()->max('power');
    }

    public function getMaxRoleAlias($max=0){
        if($max == 0)
            $max = $this->maxRole();

        $role = $this->roles()->where('power', $max)->first();

        if($role != null)
            return $role->alias;

        return false;
    }

    public function getMaxRoleName($max=0){
        if($max == 0)
            $max = $this->maxRole();

        $role = $this->roles()->where('power', $max)->first();

        if($role != null)
            return $role->role_name;

        return false;
    }


    public function hasAnyRole($roles){

        if(is_array($roles)){
            if($this->roles()->whereIn('power', $roles)->first())
                return true;
        }
        else{
            
            if($this->hasRole($roles)){
                return true;
            }
        }

        return false;
    }

    public function hasRole($role){
        if($this->roles()->where('power', $role)->first())
            return true;
        return false;
    }
    
}