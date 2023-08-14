<?php

namespace App\Http\Controllers;

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
//         'status' => true,
//         'requests' => $requests
//     ]);
// }
public function approveRequest(Request $request, $post)
{

    if ($request->posttype == 'rent') {
        $post = RentPost::withoutGlobalScopes()->find($post);
    } else {
        $post = SalePost::withoutGlobalScopes()->find($post);

    }

    if ($post) {
        $post->approval = true;
        $post->save();

        return response()->json([
            'status' => true,
            'requested_post' => $post
        ]);
    }

    return response()->json([
        'status' => false,
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
            'status' => true,
            'requested_post' => $post
        ]);
    }

    return response()->json([
        'status' => false,
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
