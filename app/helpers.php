<?php
use App\Models\Badword;
use Illuminate\Support\Str;

if (! function_exists('drf')) 
{
function drf ($name)
{
   return strtoupper($name);
}
}

if (! function_exists('badWords')) 
{
    function badWords($text)
   {
       $bad_words=Badword::all()->pluck('word')->toArray();
       $text_array=explode(" ",$text);
       $cnt=0;
       
      foreach($text_array as &$word)
      {
        if(in_array($word, $bad_words))
        {
          if(strlen($word)==2)
          { 
            $new_word='*'.$word[1];
          }
          else
          {
            $new_word=Str::mask($word,'*',-1*(strlen($word)-1),strlen($word)-2);
          }
          $word=$new_word;
      
          $cnt++;
         
        }
      }
     
        $new_text=implode(" ",$text_array);
        return [$new_text,$cnt];
   }

}
?>