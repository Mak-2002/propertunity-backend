<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $property = Property::findOrFail($request->property_id);
        $images = $property->image_urls;
        return response()->json($images);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $property)
    {
        $property = Property::findOrFail($property);
        $uploaded_image = $request->file('image');
        $ext = $uploaded_image->getClientOriginalExtension();
        $image_object = $property->image_urls()->create(['url'=>'']);
        $file_name = $image_object->id.'.'.$ext;
        $file_name = $uploaded_image->storeAs('public/images', $file_name);
        $url = asset('storage/images/'.basename($file_name));
        $image_object->url = $url;
        $image_object->save();
        return response([
            'status' => true,
            'message' => 'image stored successfully',
            'url' => $image_object->url,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}
