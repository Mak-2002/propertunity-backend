<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function myProfile()
    {
        $user=User::where('id', Auth::user()->id)->first();
        return response()->json([
            'status' => true ,
            'user' => $user
        ]);
    }

    public function editProfile(Request $request)
{
    $validated = $request->validate([
        'name' => 'string',
        'password' => 'min:6',
    ]);

    $user = User::where('id', Auth::user()->id)->first();

    // Check if the request data includes any post attributes
    $userAttributes = array_intersect_key($request->all(), $user->getAttributes());
    $hasUserAttributes = !empty($userAttributes);

    if ($hasUserAttributes) {
        // Bcrypt the password if it has been changed
        if (isset($userAttributes['password'])) {
            $userAttributes['password'] = bcrypt($userAttributes['password']);
        }

        $user->update($userAttributes);
    }

    return response([
        'status' => true,
        'user' => $user,
    ]);
}
}
