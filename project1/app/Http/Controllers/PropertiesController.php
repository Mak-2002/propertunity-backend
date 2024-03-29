<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Commercial;
use App\Models\Favorite;
use App\Models\House;
use App\Models\Land;
use App\Models\Office;
use App\Models\Property;
use App\Models\Rating;
use App\Models\RatingAspect;
use App\Models\RentPost;
use App\Models\RentRequest;
use App\Models\SalePost;
use App\Models\SaleRequest;
use App\Models\Villa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PropertiesController extends Controller
{

    /**
     * Retrieves all the property posts made by user
     */
    public function my_posts_index()
    {
        $user_id = Auth::user()->id;
        $data = [
            'sale_posts' => SalePost::withoutGlobalScopes()->whereHas('user', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->get(),
            'rent_posts' => RentPost::withoutGlobalScopes()->whereHas('user', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->get(),
        ];
        return response($data);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'category' => ucfirst($request->category),
            'rooms' => $request->rooms,
            'area_min' => $request->area_min,
            'area_max' => $request->area_max,
            'price_min' => $request->price_min,
            'price_max' => $request->price_max,
        ];

        if ($request->posttype == 'sale')
            $posts = SalePost::latest();
        elseif ($request->posttype == 'rent')
            $posts = RentPost::latest();
        else {
            $rent_posts = RentPost::latest()->filter($filters)->with('property')->get();
            $sale_posts = SalePost::latest()->filter($filters)->with('property')->get();
            $posts = $rent_posts->concat($sale_posts);
            return response($posts);
        }
        $posts = $posts->filter($filters)->with('property')->get();
        // dd($posts);
        return response($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'posttype' => 'required|in:sale,rent',
            'property_type' => 'required|in:House,Villa,Apartment,Commercial,Land,Office',
            'price' => 'required_if:posttype,sale|numeric',
            'monthly_rent' => 'required_if:posttype,rent|numeric',
            'max_duration' => 'required_if:posttype,rent|integer',
            'view_plan_id' => 'integer|exists:view_plans,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
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

        // Create category to set specific attributes related to the category of property
        $category = null;
        if ($validated['property_type'] == 'Land')
            $category = new Land;
        else {
            switch ($validated['property_type']) {
                case 'House':
                    $category = new House;
                    $category->pool = $validated['pool'] ?? null;
                    $category->garden = $validated['garden'] ?? null;
                    break;
                case 'Villa':
                    $category = new Villa;
                    $category->gym = $validated['gym'] ?? null;
                    $category->pool = $validated['pool'] ?? null;
                    $category->garden = $validated['garden'] ?? null;
                    $category->security_gard = $validated['security_gard'] ?? null;
                    break;
                case 'Apartment':
                    $category = new Apartment;
                    $category->gym = $validated['gym'] ?? null;
                    $category->pool = $validated['pool'] ?? null;
                    $category->elevator = $validated['elevator'] ?? null;
                    $category->security_gard = $validated['security_gard'] ?? null;
                    break;
                case 'Commercial':
                    $category = new Commercial;
                    $category->elevator = $validated['elevator'] ?? null;
                    $category->security_gard = $validated['security_gard'] ?? null;
                    break;
                case 'Office':
                    $category = new Office;
                    $category->security_gard = $validated['security_gard'] ?? null;
                    $category->elevator = $validated['elevator'] ?? null;
                    break;
            }

            $category->room_count = $validated['room_count'];
            $category->bathroom_count = $validated['bathroom_count'];
            $category->kitchen_count = $validated['kitchen_count'];
            $category->storey = $validated['storey'];
            $category->balkony = $validated['balkony'] ?? null;
            $category->parking = $validated['parking'] ?? null;
            $category->security_cameras = $validated['security_cameras'] ?? null;
            $category->{'Wi-Fi'} = $validated['Wi-Fi'];
        }
        $category?->save();

        // Create the Property of previous category
        $property = new Property;
        $property->user_id = Auth::user()->id;
        $property->name = $validated['name'];
        $property->latitude = $validated['latitude'];
        $property->longitude = $validated['longitude'];
        $property->address = $validated['address'];
        $property->about = $validated['about'];
        $property->area = $validated['area'];
        $property->category_type = class_basename($category);
        $property->category_id = $category->id;
        $property->save();

        // Create the post viewing the previous property
        $post = null;
        if ($validated['posttype'] == 'sale') {
            $post = new SalePost;
            $post->user_id = Auth::user()->id;
            $post->property_id = $property->id;
            $post->price = $validated['price'];
            $post->save();
            $post->refresh();
            $post->load('property');
        } else {
            $post = new RentPost;
            $post->user_id = Auth::user()->id;
            $post->property_id = $property->id;
            $post->monthly_rent = $validated['monthly_rent'];
            $post->max_duration = $validated['max_duration'];
            $post->view_plan_id = $validated['view_plan_id'] ?? null;
            $post->save();
            $post->refresh();
            $post->load('property');
        }

        return response([
            'success' => true,
            'message' => 'Post created successfully',
            'post' => $post,
        ]);
    }

    public function show(Request $request, $post)
    {
        if ($request->posttype == 'sale')
            $post = SalePost::withoutGlobalScopes()->find($post);
        else if ($request->posttype == 'rent')
            $post = RentPost::withoutGlobalScopes()->find($post);
        return response()->json($post);
    }

    public function update(Request $request, $post)
    {
        $validated = $request->validate([
            'posttype' => 'in:sale,rent',
            'property_type' => 'in:House,Villa,Apartment,Commercial,Land,Office',
            'price' => 'numeric',
            'monthly_rent' => 'numeric',
            'max_duration' => 'integer',
            'view_plan_id' => 'integer|exists:view_plans,id',
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

        // Retrieve the post by ID
        if ($request->posttype == 'sale')
            $post = SalePost::findOrFail($post);

        else if ($request->posttype == 'rent')
            $post = RentPost::findOrFail($post);

        // Check if the request data includes any post attributes
        $postAttributes = array_intersect_key($request->all(), $post->getAttributes());
        $hasPostAttributes = !empty($postAttributes);

        // Check if the request data includes any property attributes
        $propertyAttributes = array_intersect_key($request->all(), $post->property->getAttributes());
        $hasPropertyAttributes = !empty($propertyAttributes);

        // Update the post and property attributes if there are any post or property attributes in the request data
        if ($hasPostAttributes) {
            $post->update($postAttributes);
            $post->visibility = false;
            $post->approval = false;
        }
        if ($hasPropertyAttributes) {
            $post->property->update($propertyAttributes);
            $post->visibility = false;
            $post->approval = false;
        }
        $post->save();
        // Return a response with the updated post and property data
        return response([
            'success' => true,
            'post' => $post,
        ]);
    }

    public function destroy(Request $request, $post)
    {
        if ($request->posttype == 'sale')
            // $property = SalePost::withoutGlobalScope('visibility')->findOrFail($post)->with('property');
            $property = SalePost::withoutGlobalScopes()->find($post);

        if ($request->posttype == 'rent')
            // $property = RentPost::withoutGlobalScope('visibility')->findOrFail($post)->with('property');
            $property = RentPost::withoutGlobalScopes()->find($post);

        $property->delete();
        return response([
            'success' => true,
            'message' => 'Post deleted successfully',
        ]);
    }

    public function favorites(request $request)
    {
        $salefavs = SalePost::whereHas(
            'favorable_by',
            fn ($query) =>
            $query->where('user_id', Auth::user()->id)
        )->get();


        $rentfavs = RentPost::whereHas(
            'favorable_by',
            fn ($query) =>
            $query->where('user_id', Auth::user()->id)
        )->get();

        $favs = $rentfavs->concat($salefavs);

        // $favs = $salefavs->concat($rentfavs);
        return response()->json($favs);
    }
    public function change_favorite_state(Request $request, $post)
    {
        $favorite = null;
        $is_sale_post = ($request->posttype == 'sale');

        $user = Auth::user();

        if ($is_sale_post)
            $favorite = Favorite::where('user_id', $user->id)->where('sale_post_id', $post);
        else
            $favorite = Favorite::where('user_id', $user->id)->where('rent_post_id', $post);

        if ($favorite->exists()) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted from favorites successfully',
            ]);
        }


        $favorite = new Favorite;
        $favorite->setRelation('user', $user);
        $favorite->user_id = $favorite->user->id;

        if ($is_sale_post) {
            $favorite->setRelation('sale_post', SalePost::findOrFail($post));
            $favorite->sale_post_id = $favorite->sale_post->id;
        } else {
            $favorite->setRelation('rent_post', RentPost::findOrFail($post));
            $favorite->rent_post_id = $favorite->rent_post->id;
        }

        $favorite->save();

        return response()->json([
            'success' => true,
            'message' => 'Added to favorites successfully',
        ]);
    }

    public function change_visibility(Request $request, $post)
    {
        if ($request->posttype == 'sale')
            $post = SalePost::withoutGlobalScopes()->find($post);
        if ($request->posttype == 'rent')
            $post = RentPost::withoutGlobalScopes()->find($post);

        // dd($post);
        $post->visibility = !$post->visibility;

        $post->save();
        return $post;
    }
    public function rate(Request $request, RentPost $post)
    {
        if ($request->services) {
            $rate1 = Rating::where('rent_post_id', $post->id)->where('rating_aspect_id', 1)->first();
            if (is_null($rate1)) {
                $rate1 = new Rating;
                $rate1->rent_post_id = $post->id;
                $rate1->rating_aspect_id = 1;
                $rate1->setRelation('ratingAspect', RatingAspect::findOrFail(1));
            }
            $rate1->sum = $rate1->sum + $request->services;
            $rate1->count += 1;
            $rate1->avg = $rate1->sum / $rate1->count;
            $rate1->save();
        }
        if ($request->location) {
            $rate2 = Rating::where('rent_post_id', $post->id)->where('rating_aspect_id', 2)->first();
            if (is_null($rate2)) {
                $rate2 = new Rating;
                $rate2->rent_post_id = $post->id;
                $rate2->rating_aspect_id = 2;
                $rate2->setRelation('ratingAspect', RatingAspect::findOrFail(2));
            }
            $rate2->sum = $rate2->sum + $request->location;
            $rate2->count += 1;
            $rate2->avg = $rate2->sum / $rate1->count;
            $rate2->save();
        }
        if ($request->cleanliness) {
            $rate3 = Rating::where('rent_post_id', $post->id)->where('rating_aspect_id', 3)->first();
            if (is_null($rate3)) {
                $rate3 = new Rating;
                $rate3->rent_post_id = $post->id;
                $rate3->rating_aspect_id = 3;
                $rate3->setRelation('ratingAspect', RatingAspect::findOrFail(3));
            }
            $rate3->sum = $rate3->sum + $request->cleanliness;
            $rate3->count += 1;
            $rate3->avg = $rate3->sum / $rate1->count;
            $rate3->save();
        }
        $rate = Rating::where('rent_post_id', $post->id)->get();

        return response()->json($rate);
    }
}
