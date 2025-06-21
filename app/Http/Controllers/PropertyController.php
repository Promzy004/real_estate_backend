<?php

namespace App\Http\Controllers;

use App\Models\property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PropertyController extends Controller
{
    public function upload (Request $request) {

            $request->validate([
                'title' => 'required|string|min:5|max:20',
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpg,jpeg,png|max:5120',
                'property_type' => 'required|string',
                'location' => 'required',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'bed' => 'required|numeric',
                'room' => 'required|numeric',
                'bath' => 'required|numeric',
                'square_meter' => 'required|string',
            ]);

            $images = $request->file('images');

            //gets the first image and store it in a variable as thumbnail image
            $thumbnail_path = $images[0]->store('properties', 'public');
    
            $property = property::create([
                'title' => $request->title,
                'thumbnail_image' => $thumbnail_path,
                'property_type' => $request->property_type,
                'location' => $request->location,
                'price' => $request->price,
                'description' => $request->description,
                'bed' => $request->bed,
                'room' => $request->room,
                'bath' => $request->bath,
                'square_meter' => $request->square_meter,
                'user_id' => $request->user()->id
            ]);

            //iterate through all images and saves them individually
            foreach ($images as $image) {
                $path = $image->store('properties', 'public');
                PropertyImage::create([
                    'images' => $path,
                    'property_id' => $property->id
                ]);
            }

            $prop = $property->with('property_images')->first();
    
            return response()->json([
                'message' => 'uploaded successfully',
                'property' => $prop
            ], 200);

    }
}
