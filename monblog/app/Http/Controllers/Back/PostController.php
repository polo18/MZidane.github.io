<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Models\{Post, Category};
use App\DataTables\PostsDataTable;
use App\Http\Controllers\Controller;
use App\Repositories\PostRepository;
use App\Http\Requests\Back\PostRequest;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PostsDataTable $dataTable)
    {
        return $dataTable->render('back.shared.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $post = null;
        if ($id) {
            $post = Post::findOrFail($id);
            if ($post->user_id === auth()->id()) {
                $post->title .= ' (2)';
                $post->slug .= '-2';
                $post->active = false;
            } else {
                $post = null;
            }
        }

        $categories = Category::all()->pluck('title', 'id');

        return view('back.posts.form', compact('post', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostRequest  $request
     * @param  PostRepository  $repository
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request, PostRepository $repository)
    {
        $repository->store($request);

        return back()->with('ok', __('The post has been successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all()->pluck('title', 'id');
    
        return view('back.posts.form', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostRequest  $request
     * @param  \App\Models\Post  $post
     * @param  PostRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post, PostRepository $repository)
    {
        $repository->update($post, $request);

        return back()->with('ok', __('The post has been successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     */
    public function destroy(Post $post)
    {
        $post->delete();
    
        return response()->json();
    }
}
