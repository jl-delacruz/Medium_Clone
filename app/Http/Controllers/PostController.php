<?php

namespace App\Http\Controllers;


use App\Http\Requests\PostCreateRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get logs for database queries
        // DB::listen(function ($query) {
        //     \Illuminate\Support\Facades\Log::info($query->sql);
        // });

        $user = auth()->user();
        //get query for posts with user and media and claps count
        $query = Post::with('user', 'media')
            ->withCount('claps')
            ->latest();

        //only allow following users to see post
        if ($user) {
            $id = $user->following()->pluck('users.id');
            $id = $id->push($user->id); //add your own id to see all your post in all button
            $query->whereIn('user_id', $id);
        }
        $posts = $query->simplePaginate(5);

        // dd($posts);
        return view('post.index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        // Use validated() to get only the validated fields from the FormRequest
        $data = $request->validated();

        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']);

        // Create the post
        $post = Post::create($data);
        // Only add media if image is uploaded

        $post->addMediaFromRequest('image')->toMediaCollection();


        return redirect()->route('dashboard')
            ->with('success', 'Post created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        return view('post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        $categories = Category::all();
        return view('post.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {

        // Check if the authenticated user is the owner of the post
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        //validate
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image' => 'sometimes|image|max:2048',
        ]);


        // Update the post
        $post->update($data);

        // If an image is uploaded, add it to the media collection
        if ($request->hasFile('image')) {
            $post->clearMediaCollection(); // Clear previous images if any
            $post->addMediaFromRequest('image')->toMediaCollection();
        }

        return redirect()->route('myPosts')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Check if the authenticated user is the owner of the post
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        // Delete the post
        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Post deleted successfully!');
    }

    public function category(Category $category)
{
    $user = auth()->user();
    $query = Post::with('user', 'media')
        ->withCount('claps')
        ->where('category_id', $category->id) // ✅ filter by category
        ->latest();

    if ($user) {
        $followedIds = $user->following()->pluck('users.id');
        $followedIds->push($user->id); // include yourself
        $query->whereIn('user_id', $followedIds);
    }

    $posts = $query->simplePaginate(5);

    $categories = Category::all();

    return view('post.index', compact('posts', 'categories', 'category'));
}


    public function myPosts()
    {

        $user = auth()->user();
        $posts = $user->posts()
            ->with('user', 'media')
            ->withCount('claps')
            ->latest()
            ->simplePaginate(5);

        return view('post.index', compact('posts'));
    }
}
