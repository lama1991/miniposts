<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\User;

use Illuminate\Http\Request;

class AdminController extends Controller
{
   
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function all()
    {
        
        $users=User::all();
        return view('showallusers',compact('users'));
    }
    public function edit($id)
    {
        $user=User::find($id);
        $roles=Role::all();
        // $user_roles_ids=array();
        // foreach($user->roles as $role)
        // {
        //     $user_roles_ids[]=$role->id;
        // }
       $user_roles_ids=$user->roles->pluck('id')->toArray();
       return view('edit_user',compact('user','roles','user_roles_ids'));
        
    }
    public function update($id,Request $request)
    {
        $user=User::find($id);
        
        $user->roles()->sync($request['role_id']);
       
       return redirect('all_users');
    }
    public function delete($id)
    {
        $user=User::find($id);
        $user->delete();
        return redirect('all_users');
    }
   
}
