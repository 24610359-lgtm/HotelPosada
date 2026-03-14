<?php
namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;


class PostController extends Controller
{
    public function index()
{
    return Post::with('user')->latest()->get();
}

public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'body' => 'required|string'
    ]);

    $post = Post::create($validated);

    return response()->json($post, 201);
}

public function show(Post $post)
{
    return $post->load('user');
}

}
