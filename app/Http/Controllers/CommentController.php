<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function showCommentByArticle($article_id)
    {
        $comments = Comment::where('article_id',$article_id)->get();
        return response(['comments' => $comments],200);
    }

    public function create(Request $request, int $article_id)
    {
       auth()->user()->comments()->create([
           'description' => $request->description,
           'article_id' => $article_id
       ]);
       return response(['message' => 'success'],200);
    }

    public function remove(int $id)
    {
        auth()->user()->comments()->find($id)->delete();
        return response(['message' => 'commentaire supprimer']);
    }

    public function update(Request $request, int $id)
    {
      $data = $request->validate([
            'description' => ['required','string']
      ]);
      auth()->user()->comments()->find($id)->update($data);
      return response(['message' => 'commentaire mise a jour']);

    }
}
