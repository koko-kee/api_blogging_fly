<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(int $id)
    {
        $isLike = Like::where('user_id',auth()->user()->id)
            ->where('article_id',$id)
            ->exists();
        if($isLike)
        {
            $isLike = Like::where('user_id',auth()->user()->id)
                ->where('article_id',$id)
                ->delete();
            return response(['message' => 'unlike'],201);
        }else{
            Like::create([
               'user_id' => auth()->user()->id,
               'article_id' => $id
            ]);
           return response(['message' => 'like'],201);
        }
    }



    public function islike(int $id)
    {
        $isLike = Like::where('user_id',auth()->user()->id)
            ->where('article_id',$id)
            ->exists();
        if($isLike)
        {
            return response(['message' => 'like'],201);
        }else{
            return response(['message' => 'unlike'],201);
        }
    }
}
