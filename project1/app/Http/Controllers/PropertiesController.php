<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\House;
use App\Models\RentPost;
use App\Models\SalePost;
use App\Models\User;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    public function index(Request $request)
    {
        $max=$request->max;
        if($request->posttype=='sale'){
        $posts = SalePost::latest()->filter([
            'search' => $request->search,
            'type' => $request->type,
            'min' =>$request->min,////range
            'rooms' =>$request->rooms
              ////price range

            ])->with('Property')->get();
        }
            elseif($request->posttype=='rent'){
                $posts = RentPost::latest()->filter([
                    'search' => $request->search,
                    'type' => $request->type,
                    'area' =>$request->area,////range
                    'rooms' =>$request->rooms
                      ////monthly rent
        
                    ])->with('Property')->get();
                    
            }
            dd($posts);
        //   return ($posts->toJSON());
    }

    public function show(Request $request)
    {
        $property=SalePost::where('id',$request->id)->with('property')->get();
        return ($property->toJSON());
    }
    
    public function favorites(request $request)
    {   
        if($request->posttype=='sale'){
            
        $favs = SalePost::whereHas(
            'favorable_by',
            fn($query) =>
            $query->where('user_id', $request->id)
        )->with('property')->get();

    }
        
        if($request->posttype=='rent'){
            $favs = RentPost::whereHas(
                'favorable_by',
                fn($query) =>
                $query->where('user_id', $request->id)
            )->with('property')->get();}


        return response()->json($favs);
    }
    public function change_favorite_state(Request $request,$post)
        {     
            if($request->posttype=='sale'){
            $favorite = Favorite::where('user_id', $request->user_id)->where('sale_post_id', $post);
            if($favorite->exists()){
                $favorite->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'deleted from favorites successfully'
                ]);
            }
            $favorite = new Favorite;
            $favorite->setRelation('user', $request->user_id);
            $favorite->user_id = $request->user_id;
            $favorite->setRelation('salePost', $post);
            $favorite->sale_post_id = $post;
            $favorite->save();
            return response()->json([
                'success' => true,
                'message' => 'added to favorites successfully'
            ]);

        }

            elseif($request->posttype=='rent'){
            $favorite = Favorite::where('user_id', $request->user_id)->where('rent_post_id', $post);
            if($favorite->exists()){
                $favorite->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'deleted from favorites successfully'
                ]);
            }
            $favorite = new Favorite;
            $favorite->setRelation('user', $request->user_id);
            $favorite->user_id = $request->user_id;
            $favorite->setRelation('rentPost', $post);
            $favorite->rent_post_id = $post;
            $favorite->save();
            return response()->json([
                'success' => true,
                'message' => 'added to favorites successfully'
            ]);
        }

       
        }
        public function test(Request $request)
        {
            $property=House::where('id',$request->id)->with('rent')->get();
            return ($property->toJSON());
        }

        public function test2(Request $request)
        {
            $favs = House::whereHasMorph(
                'rent',
                fn($query) =>
                $query->where('id', $request->id)
            )->get();
            return ($favs->toJSON());
        }
}
