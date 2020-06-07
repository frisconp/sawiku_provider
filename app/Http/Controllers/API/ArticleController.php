<?php

namespace App\Http\Controllers\API;

use App\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();

        return $this->sendResponse($articles, 'Data artikel berhasil didapatkan.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:120'],
            'content' => ['required', 'string'],
            'thumbnail' => ['required', 'image', 'max:1024'],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Data artikel tidak valid.');
        } else {
            $article = new Article;
            $article->title = $request->title;
            $article->content = $request->content;
            $article->thumbnail = $request->file('thumbnail')->store('article_thumbnails');
            $article->admin_id = $request->user('api-admin')->id;
            $article->save();

            if ($article) {
                return $this->sendResponse($article, 'Artikel berhasil ditambahkan.', 201);
            } else {
                return $this->sendError(null, 'Gagal menambahkan artikel.');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);

        if ($article) {
            return $this->sendResponse($article->load('admin'), 'Artikel berhasil ditemukan.');
        } else {
            return $this->sendError(null, 'Artikel tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
