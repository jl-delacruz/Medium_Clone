<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ClapController extends Controller
{
    public function clap(Post $post)
{
    $hasClapped = $post->claps()->where('user_id', auth()->id())->exists();

    if ($hasClapped) {
        $post->claps()->where('user_id', auth()->id())->delete();
    } else {
        $post->claps()->create([
            'user_id' => auth()->id(),
        ]);
    }

    // Get the updated state after the toggle
    $hasClapped = $post->claps()->where('user_id', auth()->id())->exists();

    return response()->json([
        'success' => true,
        'count' => $post->claps()->count(),
        'hasClapped' => $hasClapped,
    ]);
}

}