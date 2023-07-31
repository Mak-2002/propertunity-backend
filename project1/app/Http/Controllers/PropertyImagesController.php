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
    public function store(Request $request)
    {
        $property = Property::findOrFail($request->property_id);
        $uploaded_image = $request->file('image');
        $property->increment('image_count');
        $path = $uploaded_image->store('images');
        $url = asset($path);
        $image = $property->images()->create([
            'url' => $url
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
