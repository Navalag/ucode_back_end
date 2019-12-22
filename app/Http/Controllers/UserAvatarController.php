<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserAvatarController extends Controller
{
    /**
     * Store a new user avatar.
     *
     * @return Response
     * @throws
     */
    public function store()
    {
        request()->validate([
            'avatar' => ['required', 'image']
        ]);

        auth()->user()->update([
            'avatar_src' => request()->file('avatar')->store('avatars', 'public')
        ]);

        return response([], 204);
    }
}
