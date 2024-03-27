<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
class Post extends Model
{
    use HasFactory,SoftDeletes;
   // protected $guarded=['id','user_id'];
    protected  $fillable=['title','desc','content','user_id'];
  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
  
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function getMaskedContentAttribute()
    {
        list($new_text,$cnt)=badWords($this->content);
       
        return $new_text;
       
    }
    public function getCntBdsAttribute()
    {
        list($new_text,$cnt)=badWords($this->content);
        return $cnt;
    }

}
