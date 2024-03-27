@foreach($users as $user)
<h4>{{$user->name}} : 
@foreach($user->roles as $role)

{{$role->role_name}}

@endforeach
<br>
@foreach($user->images as $image)
             
               <td> <img src="{$l}" width="200" height="200"></td>
               

               @endforeach
<a href="{{ route('download_ids', ['user' => $user->id]) }}">download Ids</a>
<a href="{{ route('edit', ['id' => $user->id]) }}">edit</a>
<hr>
@endforeach



<a href="home">home</a>
<a href="{{url('/')}}">welcome</a>
