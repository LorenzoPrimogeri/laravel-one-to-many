<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:250',
            'content' => 'required',

        ]);
        $post = $request->all();
        $newPost = new Post();
        $newPost->fill($post);
        $slug = Str::slug($newPost->title);
        $found = Post::where('slug', $slug)->first();
        $count = 1;
        $altSlug = $slug;
        while ($found) {
            $altSlug = $slug . '_' . $count;
            $count++;
            $found = Post::where('slug', $altSlug)->first();
        }
        $newPost->slug = $altSlug;
        $newPost->save();
        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        return view('admin.posts.edit', compact('post'));
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
        $post = new Post();
        $post = Post::findOrFail($id);
        $data = $request->all();
        $post->fill($data);
        $slug = Str::slug($post->title);
        $found = Post::where('slug', $slug)->first();
        $count = 1;
        $altSlug = $slug;
        while ($found) {
            $altSlug = $slug . '_' . $count;
            $count++;
            $found = Post::where('slug', $altSlug)->first();
        }
        $post->slug = $altSlug;
        $post->save();
        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.posts.index');
    }
}
