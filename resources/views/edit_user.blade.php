{{$user->name}} 
<form method="post" action="{{route('edit', ['id' => $user->id]) }}">
@csrf

 <select name='role_id[]' multiple='multiple' >
    @foreach($roles as $role)
    <option value='{{$role->id}}'
        @if(in_array($role->id, $user_roles_ids)) selected='selected' @endif >{{$role->role_name}}</option> 
    @endforeach 
</select>


<input type="submit">
</form>
    
  
             


       