<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HouseResource;
use App\Models\House;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return HouseResource::collection(House::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'address' => 'required',
            'area_id' => 'required',
            'number_of_room' => 'required|numeric|integer',
            'number_of_toilet' => 'required|numeric|integer',
            'number_of_balcony' => 'required|numeric|integer',
            'rent' => 'required|numeric',
            'featured_image' => 'required|mimes:jpeg,png,jpg',
            'images.*' => 'required|mimes:jpeg,png,jpg',
        ]);

        //handle featured image
        $featured_image = $request->file('featured_image');
        if ($featured_image) {
            // Make Unique Name for Image
            $currentDate = Carbon::now()->toDateString();
            $featured_image_name = $currentDate . '-' . uniqid() . '.' . $featured_image->getClientOriginalExtension();

            // Check Dir is exists

            if (!Storage::disk('public')->exists('featured_house')) {
                Storage::disk('public')->makeDirectory('featured_house');
            }

            // Resize Image  and upload
            $cropImage = Image::make($featured_image)->resize(400, 300)->stream();
            Storage::disk('public')->put('featured_house/' . $featured_image_name, $cropImage);
        }

        $data = [];
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->extension();
                $file->move(public_path() . '/images/', $name);
                $data[] = $name;
            }
        }

        $house = House::create([
            'address' => $request->address,
            'contact' => $request->contact,
            'area_id' => $request->area_id,
            'user_id' => 56,
            'number_of_toilet' => $request->number_of_toilet,
            'number_of_room' => $request->number_of_room,
            'number_of_balcony' => $request->number_of_balcony,
            'rent' => $request->rent,
            'images' => json_encode($data),
            'featured_image' => $featured_image_name,
        ]);

        return $house;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        return $house;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function edit(House $house)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, House $house)
    {
        $this->validate($request, [
            'address' => ['required'],
            'area_id' => ['required'],
            'number_of_room' => ['required', 'numeric', 'integer'],
            'number_of_toilet' => ['required', 'numeric', 'integer'],
            'number_of_balcony' => ['required', 'numeric', 'integer'],
            'rent' => ['required', 'numeric'],
            'featured_image' => ['required', 'mimes:jpeg,png,jpg'],
            'images.*' => ['required', 'mimes:jpeg,png,jpg'],
        ]);

        $house->update([
            'address' => $request->address,
            'contact' => Auth::user()->contact,
            'area_id' => $request->area_id,
            'number_of_toilet' => $request->number_of_toilet,
            'number_of_room' => $request->number_of_room,
            'number_of_balcony' => $request->number_of_balcony,
            'rent' => $request->rent,
            'images' => json_encode($data),
            'featured_image' => $featured_image_name,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house)
    {
        $house->delete();
        return back();
    }
}
