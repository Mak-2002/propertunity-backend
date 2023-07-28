<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Commercial;
use App\Models\Favorite;
use App\Models\House;
use App\Models\Land;
use App\Models\Office;
use App\Models\RentPost;
use App\Models\SalePost;
use App\Models\Villa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertiesController extends Controller
{
    
    /**
     * Retrieves all the property posts made by user
     */
    public function my_properties_index(){
        $data = [
            'sale_posts' => Auth::user()->sale_posts->latest,
            'rent_posts' => Auth::user()->sale_posts->latest,
        ];
        return response($data);
    }

    public function index(Request $request)
    {
        $max = $request->max;
        if ($request->posttype == 'sale') {
            $posts = SalePost::latest()->filter([ 
                'search' => $request->search,
                'type' => $request->type,
                'rooms' => $request->rooms,
                'area_min' => $request->area_min,
                'area_max' => $request->area_max,
                'price_min' => $request->price_min,
                'price_max' => $request->price_max,

            ])->with('Property')->get();
        } elseif ($request->posttype == 'rent') {
            $posts = RentPost::latest()->filter([
                'search' => $request->search,
                'type' => $request->type,
                'rooms' => $request->rooms,
                'area_min' => $request->area_min,
                'area_max' => $request->area_max,
                'price_min' => $request->price_min,
                'price_max' => $request->price_max,

            ])->with('Property')->get();

        }

        return ($posts->toJSON());
    }

    public function store(Request $request)
    {  
        
        $validated = $request->validate([
            'posttype' => 'required|in:sale,rent',
            'property_type' => 'required|in:House,Villa,Apartment,Commercial,Land,Office',
            'price' => 'required_if:posttype,sale',
            'monthly_rent' => 'required_if:posttype,rent|numeric',
            'max_duration' => 'required_if:posttype,rent|integer',
            'view_plan_id' => 'integer|exists:view_plans,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'address' => 'required|string',
            'room_count' => 'required_if:property_type,House,Villa,Apartment,Commercial,Office|integer',
            'bathroom_count' => 'required_if:property_type,House,Villa,Apartment,Commercial,Office|integer',
            'kitchen_count' => 'required_if:property_type,House,Villa,Apartment,Commercial,Office|integer',
            'storey' => 'required_if:property_type,House,Villa,Apartment,Commercial,Office|integer',
            'area' => 'required',
            'about' => 'required|string|max:500',
            'balkony' => 'integer',
            'gym' => 'boolean',
            'pool' => 'boolean',
            'parking' => 'boolean',
            'security_cameras' => 'boolean',
            'elevator' => 'boolean',
            'Wi-Fi' => 'boolean',
            'security_gard' => 'boolean',
            'garden' => 'boolean',
        ]); 
        
           
        if ($validated['property_type'] == 'Land') {
            $property = new Land;
            $property->user_id = $validated['user_id'];
            $property->name = $validated['name'];
            $property->address = $validated['address'];
            $property->area = $validated['area'];
            $property->about = $validated['about'];
            $property->save();
        } else {
            switch ($validated['property_type']) {
                case 'House':
                    $property = new House;
                    $property->pool = $validated['pool'] ??null ;
                    $property->garden = $validated['garden'] ?? null;
                    break;
                case 'Villa':
                    $property = new Villa;
                    $property->gym = $validated['gym']??null;
                    $property->pool = $validated['pool']??null;
                    $property->garden = $validated['garden']??null;
                    $property->security_gard = $validated['security_gard']??null;

                    break;
                case 'Apartment':
                    $property = new Apartment;
                    $property->gym = $validated['gym']??null;
                    $property->elevator = $validated['elevator']??null;
                    $property->security_gard = $validated['security_gard']??null;
                    break;
                case 'Commercial':
                    $property = new Commercial;
                    $property->elevator = $validated['elevator']??null;
                    $property->security_gard = $validated['security_gard']??null;
                    break;
                case 'Office':
                    $property = new Office;
                    $property->security_gard = $validated['security_gard']??null;
                    $property->elevator = $validated['elevator']??null;
                    break;

            }

            $property->user_id = $validated['user_id'];
            $property->name = $validated['name'];
            $property->address = $validated['address'];
            $property->room_count = $validated['room_count'];
            $property->bathroom_count = $validated['bathroom_count'];
            $property->kitchen_count = $validated['kitchen_count'];
            $property->storey = $validated['storey'] ;
            $property->area = $validated['area'];
            $property->about = $validated['about'];
            $property->balkony = $validated['balkony']??null;
            $property->parking = $validated['parking']?? null;
            $property->security_cameras = $validated['security_cameras']?? null;
            // $property->[Wi-Fi] = $validated['Wi-Fi'];
            $property->save();
        }
        if ($validated['posttype'] == 'sale') {
            $post = new SalePost;
            $post->user_id = $validated['user_id'];
            $post->property_type = $validated['property_type'];
            $post->property_id = $property['id'];
            $post->price = $validated['price'];
            $post->view_plan_id = $validated['view_plan_id'] ??null ;
            if ($post->save()) {
                $post = SalePost::with('property')->findOrFail($post->id);}
        } else {
            $post = new RentPost;
            $post->user_id = $validated['user_id'];
            $post->property_type = $validated['property_type'];
            $post->property_id = $property['id'];
            $post->monthly_rent = $validated['monthly_rent'];
            $post->max_duration = $validated['max_duration'];
            $post->view_plan_id = $validated['view_plan_id'] ??null ;
            if ($post->save()) {
                $post = RentPost::with('property')->findOrFail($post->id);}
        }
        

    
            return response([
                'status' => true,
                'post' => $post,
            ]);
        
    }
    
    public function show(Request $request, $id)
    {
        if ($request->posttype == 'sale') {
            $property = SalePost::where('id', $id)->with('property')->get();
            return ($property->toJSON());}

        if ($request->posttype == 'rent') {
            $property = RentPost::where('id', $id)->with('property')->get();
            return ($property->toJSON());}
    }

    public function update(Request $request , $id)
    { 
    
    // dd($request);
    $validated = $request->validate([
        'posttype' => 'in:sale,rent',
        'property_type' => 'in:House,Villa,Apartment,Commercial,Land,Office',
        'price' => 'numeric',
        'monthly_rent' => 'numeric',
        'max_duration' => 'integer',
        'view_plan_id' => 'integer|exists:view_plans,id',
        'user_id' => 'exists:users,id',
        'name' => 'string',
        'address' => 'string',
        'room_count' => 'integer',
        'bathroom_count' => 'integer',
        'kitchen_count' => 'integer',
        'storey' => 'integer',
        'area' => 'numeric',
        'about' => 'string|max:500',
        'balkony' => 'integer',
        'gym' => 'boolean',
        'pool' => 'boolean',
        'parking' => 'boolean',
        'security_cameras' => 'boolean',
        'elevator' => 'boolean',
        'Wi-Fi' => 'boolean',
        'security_gard' => 'boolean',
        'garden' => 'boolean',
    ]); 
    dd($validated);
    
         // Retrieve the post by ID
    $post = SalePost::findOrFail($id);

    // Check if the request data includes any post attributes
    $postAttributes = array_intersect_key($request->all(), $post->getAttributes());
    $hasPostAttributes = !empty($postAttributes);
       
    // Check if the request data includes any property attributes
    $propertyAttributes = array_intersect_key($request->all(), $post->property->getAttributes());
    $hasPropertyAttributes = !empty($propertyAttributes);

    // Update the post and property attributes if there are any post or property attributes in the request data
    if ($hasPostAttributes) {
        $post->update($postAttributes);
    }
    if ($hasPropertyAttributes) {
        $post->property->update($propertyAttributes);
    }

    // Return a response with the updated post and property data
    return response([
        'status' => true,
        'post' => $post,
    ]);
    }

    public function destroy(Request $request, $id)
    { 
        if ($request->posttype == 'sale') {
            $property = SalePost::where('id', $id)->with('property');
            $property->delete();
            return ('deleted successfully');}

        if ($request->posttype == 'rent') {
            $property = RentPost::where('id', $id)->with('property');
            $property->delete();
            return ('deleted successfully');}

    }

    public function favorites(request $request)
    {
        if ($request->posttype == 'sale') {

            $favs = SalePost::whereHas(
                'favorable_by',
                fn($query) =>
                $query->where('user_id', $request->id)
            )->with('property')->get();

        }

        if ($request->posttype == 'rent') {
            $favs = RentPost::whereHas(
                'favorable_by',
                fn($query) =>
                $query->where('user_id', $request->id)
            )->with('property')->get();}

        return response()->json($favs);
    }
    public function change_favorite_state(Request $request, $post)
    {
        if ($request->posttype == 'sale') {
            $favorite = Favorite::where('user_id', $request->user_id)->where('sale_post_id', $post);
            if ($favorite->exists()) {
                $favorite->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'deleted from favorites successfully',
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
                'message' => 'added to favorites successfully',
            ]);

        } elseif ($request->posttype == 'rent') {
            $favorite = Favorite::where('user_id', $request->user_id)->where('rent_post_id', $post);
            if ($favorite->exists()) {
                $favorite->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'deleted from favorites successfully',
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
                'message' => 'added to favorites successfully',
            ]);
        }

    }
    public function test(Request $request)
    {
        $property = House::where('id', $request->id)->with('rent')->get();
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
 ///////////////////update
 //   if($request->price){
    //     $post = SalePost::with('property')->findOrFail($id);
    //     $post->update($request->all());

    //   }
    //   if($request->monthly_rent||$request->max_duration){
    //     $post = RentPost::with('property')->findOrFail($id);
    //     $post->update($request->all());
   
        
    //   }
            

    //     if ($post) {
    //         $property = $post->property;
    //         if ($property) {
    //             $property->update($request->all());
    //         }
    //         $post->update($request->all());
    //     }
    //     return response([
    //         'status' => true,
    //         'post' => $post,
    //     ]);



    //             // Retrieve the post by ID
    // $post = SalePost::with('property')->findOrFail($id);

    // // Retrieve the related property
    // $property = $post->property;

    // // Update the post attributes
    // $post->update($request->all());

    // // Update the property attributes
    // $property->update($request->all());

    // // Return a response with the updated post and property data
    // return response([
    //     'status' => true,
    //     'post' => $post,
    //     'property' => $property,
    // ]);