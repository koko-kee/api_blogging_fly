<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(int $id_article)
    {
        $isLike = Like::where('user_id',auth()->user()->id)
            ->where('article_id',$id_article)
            ->exists();
        if($isLike)
        {
            $isLike = Like::where('user_id',auth()->user()->id)
                ->where('article_id',$id_article)
                ->delete();
            return response(['message' => 'unlike'],201);
        }else{
            Like::create([
               'user_id' => auth()->user()->id,
               'article_id' => $id_article
            ]);
           return response(['message' => 'like'],201);
        }
    }
}
