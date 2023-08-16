<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function upload_profile_photo(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $uploadedImage = $request->file('image');

        // Start a database transaction
        DB::beginTransaction();

        try {
            $user = Auth::user();

            // Store the uploaded image
            $storedPath = $uploadedImage->storeAs('public/profile_photos',  $user->id . '.' . $uploadedImage->getClientOriginalExtension());

            // Generate the publicly accessible URL
            $imageUrl = Storage::url($storedPath);

            $user->profile_photo_url = $imageUrl;
            $user->save();

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile photo updated successfully',
                'url' => $imageUrl,
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to store the image',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function my_profile()
    {
        return response()->json([
            'success' => true,
            'user' => Auth::user(),
        ]);
    }

    public function edit_profile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string',
            'password' => 'min:6',
        ]);

        $user = Auth::user();

        // Check if the request data includes any post attributes
        $userAttributes = array_intersect_key($request->all(), $user->getAttributes());
        $hasUserAttributes = !empty($userAttributes);

        if ($hasUserAttributes) {
            // Bcrypt the password if it has been changed
            if (isset($userAttributes['password']))
                $userAttributes['password'] = bcrypt($userAttributes['password']);

            $user->update($userAttributes);
        }

        return response([
            'success' => true,
            'user' => $user,
        ]);
    }
}
