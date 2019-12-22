<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerificationApiController extends Controller
{
    use VerifiesEmails;

    /**
     * Show the email verification notice.
     *
     */
    public function show()
    {
        //
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return Response
     */
    public function verify(Request $request)
    {
        $userID = $request['id'];

        $user = User::findOrFail($userID);

        $date = date('Y-m-d g:i:s');

        $user->email_verified_at = $date;

        $user->save();

        return response()->json('Email verified!', 200);
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request
     * @return Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json('User already have verified email!', 422);
        }

        $request->user()->sendApiEmailVerificationNotification();

        return response()->json('The notification has been resubmitted');
    }
}
