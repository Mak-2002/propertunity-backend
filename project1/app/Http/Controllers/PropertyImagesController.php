<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyImagesController extends Controller
{
    public function index(Request $request) {
        $property = Property::findOrFail($request->property_id);
        $images = $property->images;
        return response()->json($images);
    }
}
