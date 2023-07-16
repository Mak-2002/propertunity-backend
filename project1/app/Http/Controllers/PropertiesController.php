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
            'rooms' =>$request->rooms,
            'area_min' =>$request->area_min,
            'area_max' =>$request->area_max,
            'price_min'=>$request->price_min,
            'price_max'=>$request->price_max
          

            ])->with('Property')->get();
        }
            elseif($request->posttype=='rent'){
                $posts = RentPost::latest()->filter([
                    'search' => $request->search,
                    'type' => $request->type,
                    'rooms' =>$request->rooms,
                    'area_min' =>$request->area_min,
                    'area_max' =>$request->area_max,
                    'price_min'=>$request->price_min,
                    'price_max'=>$request->price_max
                      
        
                    ])->with('Property')->get();
                    
            }
            
          return ($posts->toJSON());
    }

    public function show(Request $request,$id)
    {   
        if($request->posttype=='sale'){
        $property=SalePost::where('id',$id)->with('property')->get();
        return ($property->toJSON());}
        
        if($request->posttype=='rent'){
        $property=RentPost::where('id',$id)->with('property')->get();
        return ($property->toJSON());}
    }
    
    public function destroy(Request $request,$id)
    {   
        if($request->posttype=='sale'){
        $property=SalePost::where('id',$id)->with('property');
        $property->delete();
        return ('deleted successfully');}
        
        if($request->posttype=='rent'){
        $property=RentPost::where('id',$id)->with('property');
        $property->delete();
        return ('deleted successfully');}
        
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
