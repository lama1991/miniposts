<?php

namespace App\Http\Controllers;

use App\Events\NewPostCreated;
use App\Models\Post;
use App\Models\Badword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostCollection;
use App\Http\Traits\UploadTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class PostController extends Controller
{
   use UploadTrait;
    public function __construct()
    {
    
    //normal user can only retrive his trashed posts 
     $this->middleware('trash', ['only' => ['index']]);
    
     //normal user can view and comment on all other's post
    //but can only edit and delete his posts
   $this->middleware('editDelete', ['only' => ['edit','destroy']]);

   $this->middleware('blocked', ['only' => ['create']]);
    }
   
   
    public function index(Request $request)
    {
        

         if ($request->has('trashed')) {
            $posts = Post::onlyTrashed()->get();
        } else {
            $posts =Post::all();
        }
      
      

    return view('posts.index')->with("posts",$posts);
    }
    
    public function userPosts(Request $request)
    {
        if ($request->has('trashed')) {
            $posts = Post::onlyTrashed()
                ->where('user_id','=',Auth::id())->get();
        } else {
            $posts = Auth::user()->posts;
        }

        return view('posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validation =  Validator::make($request->all(), [
            'title' => 'required|max:255',
            'desc' => 'required',
            'content'=> 'required',
            'image'=> 'mimes:jpg,bmp,png',
         ]);
         $data= $validation->validated();
         $data['user_id'] = Auth::id();
       $post=Post::create($data);
       
        if($request->hasFile('images'))
        {
            $links=[];
            $images=$request->file('images');
         $image_pathes=$this-> uploadMulti($images,'newfold', 'public');
            foreach( $image_pathes as $link)
            {
                $links[]=['link'=>$link];
            
            }
            $post->images()->createMany($links );
        }
        event(new NewPostCreated($post));
        return redirect('/posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
          $post = Post::find($id);
        $comments=$post->comments;
       
        return view('posts.show',compact('post','comments'));
        
      
       

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $post = Post::find($id);
        return view('posts.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd('hi');
        $post = Post::find($id);
        $input = $request->all();
        $post->update($input);
        $post->save();

        return redirect('/posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $post=Post::find($id);
       if($post->comments())
        $post->comments()->delete();
       $post->delete();

        return redirect('/posts');
    }
    public function restore($id)
    {
        Post::withTrashed()->find($id)->restore();
       // dd( Post::withTrashed()->count());
        return redirect()->back();
    }

    /**
     * restore all post
     *
     * @return response()
     */
    public function restoreAll()
    {
        Post::onlyTrashed()->restore();
        return redirect()->route('/posts');
    }
   
    
}
