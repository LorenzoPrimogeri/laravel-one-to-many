<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
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
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|max:250',
                'content' => 'required|min:10|max:250',
                'category_id' => 'nullable|exists:categories,id',

            ],
            [
                'title.required' => 'Inserire il titolo',
                'title.max' => 'hai superato i :attribute caratteri',
                'content.required' => 'Inserire il contenuto del post',
                'content.max' => 'hai superato i :attribute caratteri',
                'content.min' => 'devi almeno inserire :attribute caratteri',
                'category_id.exists' => 'la categoria non esiste '

            ]
        );
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
        $category = Category::find($post->category_id);

        return view('admin.posts.show', compact('post', 'category'));
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
        $categories = Category::all();

        return view('admin.posts.edit', compact('post', 'categories'));
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
        $request->validate(
            [
                'title' => 'required|max:250',
                'content' => 'required|min:10|max:250',
                'category_id' => 'nullable|exists:categories,id',

            ],
            [
                'title.required' => 'Inserire il titolo',
                'title.max' => 'hai superato i :attribute caratteri',
                'content.required' => 'Inserire il contenuto del post',
                'content.max' => 'hai superato i :attribute caratteri',
                'content.min' => 'devi almeno inserire :attribute caratteri',
                'category_id.exists' => 'la categoria non esiste '

            ]
        );

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
