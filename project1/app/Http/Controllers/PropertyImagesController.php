<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Property;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'image' => 'required|image',
        ]);

        $property = Property::findOrFail($property);
        $uploadedImage = $request->file('image');

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the image object
            $imageObject = new Image;
            $imageObject->setRelation('property', $property);
            $imageObject->property_id = $property->id;
            $imageObject->url = '';
            $imageObject->save();

            // Store the uploaded image
            $storedPath = $uploadedImage->storeAs('public/property_images', $imageObject->id . '.' . $uploadedImage->getClientOriginalExtension());

            // Generate the publicly accessible URL
            $imageUrl = Storage::url($storedPath);
            $imageObject->url = $imageUrl;
            $imageObject->save();

            // dd($property);

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Image stored successfully',
                'url' => $imageUrl,
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
