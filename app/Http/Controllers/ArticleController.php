<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public  function index()
    {
        $articles = Article::with(['user' => function ($query) {
            $query->select('id','name','username','email');
        }])
            ->select(['id','description','media','type_media','user_id'])
            ->get();
        return response($articles,200);
    }

    public function show(int $id)
    {
        $article = Article::select(['id','description','user_id','categorie_id'])
            ->where('id' , $id)
            ->first();
        return response(['article' => $article],200);
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'description' => ['required'],
            'media' => ['required','file'],
            'categorie_id' => ['required','integer']

        ]);

        if ($validator->fails()) {

            return  response(['error'=>  $validator->errors()],422);
        }


        $extension = $request->file('media')->getClientOriginalExtension();
        $mediaType = '';
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $mediaType = 'image';
        } elseif ($extension === 'mp4') {
            $mediaType = 'video';
        } else {
            return redirect()->back()->withErrors(['media' => 'Le média doit être une image ou une vidéo.']);
        }

        $media = $request->file('media');
        $mediaName = time() . '.' . $extension;
        $mediaPath = $media->storeAs('media', $mediaName, 'public');
        $mediaUrl = asset('storage/' . $mediaPath);

        auth()->user()->articles()->create([
            'description' => $request->description,
            'media' => $mediaUrl,
            'type_media' => $mediaType,
            'categorie_id' => $request->categorie_id
        ]);

        return response()->json(['media_url' => $mediaUrl]);
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
