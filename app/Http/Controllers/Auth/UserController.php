<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use  App\Http\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    use UploadTrait;
    public function all()
    {
        
        $users=User::all();
        return view('showallusers',compact('users'));
    }
    public function unBlock()
    {
        
        $user=Auth::user();
        $posts=$user->posts()->get()->where('cnt_bds','>=',5);
        if(!$posts->first())
        {
           
        $user->is_blocked=0;
            
        $user->save();

        }
        if ($user->is_blocked==0)
        {
           $msg= "unblocked successfully";
        }
        else
        {
            $msg="your posts still contain bad words";
        }
        return view('unblock_result',compact('msg'));
    }
    public function uploadIds(Request $request)
    {
        $user=Auth::user();
       if($request->hasFile('identifier'))
       {
        $file=$request->file('identifier');
        $path=$this->uploadOne($file, 'privates', 'local');
        $user->identifier=$path;
         $user->save();
    }

    }
    public function downloadId()
    {
       $user=Auth::user();
       return $this->downloadFile('local',$user->identifier);
        
    }
}
