<?php   namespace App\Modules\Backend\Controllers;


use App\Libs\Utils\Vii;
//use Request;
use Illuminate\Http\Request;
use App\Modules\Backend\Requests\AdminPostRequest;
use App\Modules\Backend\Requests\AdminEditPostRequest;

use App\Models\Admin;
use App\Models\Role;


class AdminController extends BaseController{
    
    //private static $prefix_url = "accounts";
    
    public function __construct(){
        parent::__construct();

        $actions = request()->route()->getAction();
        $this->prefixUrl = $actions['prefix'];
    }
    
    public function getUserList(Request $request){

        //dd($this->guard->user()->getMaxRoleAlias());

        $fields = [
            'admin.*'
        ];

        $max_role = $this->guard->user()->maxRole();
        $alias = $request->user($this->guardName)->getMaxRoleAlias($max_role);
        //dd($max_role);
                
        //$user_sql = Admin::leftJoin('role', 'admin.role_id', '=', 'role.power');
        $user_sql = Admin::where('admin.active', '>=', 0);
        if($alias == 'SA'){
            $user_sql->where('admin.id', '!=', $this->guard->user()->id);

        }
        else if($alias == 'A' || $alias == 'M'){
            $user_sql->where('admin.created_by', $this->guard->user()->id);
        }

        
        $users = $user_sql->orderBy('admin.first_name', 'ASC')
            ->select($fields)->paginate(20);


        $users->setPath('');
        
        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');

        $form_qs = Vii::queryStringBuilder($append_qs);
            
        
        $users->appends($append_qs);
        
        if($request->input('page'))
            $append_qs['page'] = $request->input('page');
        
        $qs = Vii::queryStringBuilder($append_qs);
        
        
        return view(
            'Backend::admin.list-user',
            [
                'users' => $users,
                'qs' => $qs,
                'form_qs' => $form_qs,
                'prefix_url' => $this->prefixUrl
            ]
        );
    }
    
    public function getUserForm(Request $request, $id=null){
        
        $qs = Vii::queryStringBuilder($request->getQueryString());


        //$max_role = $this->guard->user()->maxRole();
        $max_role = $request->user($this->guardName)->maxRole();

        $alias = $request->user($this->guardName)->getMaxRoleAlias($max_role);

        $operator = '<';
        if($alias == 'SA' || $alias == 'A')
            $operator .= '=';

        $roles = Role::where('power', $operator, $max_role)
            ->select(['id', 'power', 'role_name'])
            ->orderBy('power', 'DESC')
            ->get();
        
        if($id != null){
            $user = Admin::find($id);
            //dd($user->roles->toArray());

            $user_roles = $user->roles()->orderBy('power', 'DESC')->get();
            $arr_role_checked = [];
            foreach($roles as $role){
                $arr_role_checked[$role->id] = false;
                foreach($user_roles as $user_role){
                    if($role->id == $user_role->id){
                        $arr_role_checked[$role->id] = true;
                        break;
                    }
                }
            }

            return view(
                'Backend::admin.edit-user',
                [
                    'qs' => $qs,
                    'user' => $user,
                    //'roles' => Vii::createOptionData($roles->toArray(), 'power', 'role_name', [''=>'---Role---']),
                    'roles' => $roles,
                    'roles_checked' => $arr_role_checked,
                    'prefix_url' => $this->prefixUrl,
                ]
            );
        }
        
        return view(
            'Backend::admin.create-user',
            [
                'qs' => $qs,
                //'roles' => Vii::createOptionData($roles->toArray(), 'power', 'role_name', [''=>'---Role---']),
                'roles' => $roles,
                'prefix_url' => $this->prefixUrl
            ]
        );
        
    }
    
    public function postUserCreate(AdminPostRequest $request){
       
        $qs = Vii::queryStringBuilder($request->getQueryString());
        
        $_form = $request->all();

        //dd($_form);
        
        $salt = md5(time() + $_form['email']);
        
        $data = [
            'first_name' => $_form['first_name'],
            'surname' => $_form['surname'],
            'password' => bcrypt($_form['password']), // \Hash::make($_form['password']),
            'email' => $_form['email'],
            'salt' => $salt,
            'role_id' => 0,
            'created_by' => $this->guard->user()->id,
            'active' => $_form['active']
        ];


        $model = new Admin($data);
        
        if($model->save()){

            $model->roles()->attach($request->input('roles',[]));

            return redirect($this->prefixUrl . $qs);
        }
        
        return redirect($this->prefixUrl . '/create' . $qs);
    }
    
    public function putUserEdit(AdminEditPostRequest $request, $id=null){
        
        $_form = $request->only('id', 'first_name', 'surname', 'email', 'role_id', 'active', 'password');
        
        $data = [
            'first_name' => $_form['first_name'],
            'surname' => $_form['surname'],
            'email' => $_form['email'],
            'role_id' => 0,
            'active' => $_form['active']
        ];
        
        if($_form['password'] != '')
            $data['password'] =  bcrypt($_form['password']);
        
        
        $model = Admin::findOrFail($_form['id']);
        if($model->fill($data)->save()){ //$model->update($data);
            $model->roles()->detach();
            $model->roles()->attach($request->input('roles',[]));
        }
        
        $qs = Vii::queryStringBuilder($request->getQueryString());
            
        return redirect($this->prefixUrl . $qs);
        
        
    }

    public function getDelete(Request $request, $id){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $user = Admin::find($id);
        if($user != null){
            $user->roles()->detach();
            $user->delete();

        }

        return redirect($this->prefixUrl . $qs);
    }
}