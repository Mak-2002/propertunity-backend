<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\RentPost;
use App\Models\SalePost;
use App\Models\ViewRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getPendingPosts()
    {
        $rentPosts = RentPost::withoutGlobalScopes()->whereNull('approval')->get();
        $salePosts = SalePost::withoutGlobalScopes()->whereNull('approval')->get();

        $posts = [
            'rent_posts' => $rentPosts,
            'sale_posts' => $salePosts,
        ];

        return $posts;
    }
//     public function checkRequests()
// {
//     $requests = ViewRequest::with(['rentPost' => function ($query) {
//         $query->withoutGlobalScope('visibility');
//     }, 'salePost' => function ($query) {
//         $query->withoutGlobalScope('visibility');
//     }])->get();

//     return response()->json([
//         'success' => true,
//         'requests' => $requests
//     ]);
// }
public function approveRequest(Request $request, $post)
{

    if ($request->posttype == 'rent') {
        $post = RentPost::withoutGlobalScopes()->find($post);
    } else if ($request->posttype == 'sale') {
        $post = SalePost::withoutGlobalScopes()->find($post);

    }

    if ($post) {
        $post->approval = true;
        $post->save();

        if ($request->posttype == 'rent') {
            $r1 = new Rating;
            $r1->rent_post_id = $post->id;
            $r1->rating_aspect_id = 1;
            $r1->sum = 0;
            $r1->count = 0;
            $r1->avg = 0;
            $r1->save();

            $r2 = new Rating;
            $r2->rent_post_id = $post->id;
            $r2->rating_aspect_id = 2;
            $r2->sum = 0;
            $r2->count = 0;
            $r2->avg = 0;
            $r2->save();

            $r3 = new Rating;
            $r3->rent_post_id = $post->id;
            $r3->rating_aspect_id = 3;
            $r3->sum = 0;
            $r3->count = 0;
            $r3->avg = 0;
            $r3->save();
    }
        return response()->json([
            'success' => true,
            'requested_post' => $post->refresh()
        ]);

    }

    return response()->json([
        'success' => false,
        'message' => 'Post not found'
    ]);
}
public function rejectRequest(Request $request,$post)
{
    if ($request->posttype == 'rent') {
        $post = RentPost::withoutGlobalScopes()->find($post);
    } else {
        $post = SalePost::withoutGlobalScopes()->find($post);

    }

    if ($post) {
        $post->approval = false;
        $post->rejection_purpose = $request->rejection_purpose;
        $post->save();

        return response()->json([
            'success' => true,
            'requested_post' => $post
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Post not found'
    ]);


}
//     public function approveRequest(Request $request, $viewRequest)
// {
//     $viewRequest = ViewRequest::find($viewRequest);
//     // $post = SalePost::where('id' , $viewRequest->sale_post_id);

//     // // Assuming $post is an instance of RentPost or SalePost
//     // $post->visibility = true; // Update the visibility of the post
//     // $post->save(); // Save the changes

//     return response()->json(['message' => 'Post visibility updated successfully.',
//            'post' => $viewRequest
//         ]);
// }
}
