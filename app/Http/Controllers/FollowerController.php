<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    /**
     * Follow a user.
     */
    public function followUnfollow(User $user)
    {
        $user->followers()->toggle(auth()->user()->id);
        return response()->json(['message' => 'Follow status toggled', 'followers' => $user->followers()->count()]);
    }
}
