<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public  function index()
    {
        $articles = Article::orderBy('created_at','desc')->get();
        return response(['articles' => $articles],200);
    }

    public function show(int $id)
    {
        $article = Article::find($id);
        return response(['article' => $article],200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required','string'],
            'description' => ['required','string']
        ]);
        \auth()->user()->articles()
            ->create([
                'title' => $request->title,
                'description' => $request->description,
                'categorie_id' => $request->categorie_id
            ]);
        return response(['message' => 'article creer avec succces'],200);
    }

    public function update(Request $request , int $id)
    {
       $data = $request->validate([
            'title' => ['required','string'],
            'description' => ['required','string'],
            'categorie_id' => ['required','exists:categories,id']
       ]);

       $article = \auth()->user()->articles()->findOrFail($id);
       $article->update($data);
       return response(['message' => 'article creer a bien ete editer'],200);
    }

    public  function delete(int $id)
    {
        $article = \auth()->user()->articles()->findOrFail($id);
        $article->delete();
        return response(['message' => 'article creer a bien ete supprimer'],200);
    }

}
