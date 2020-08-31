<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Facility;
use App\House;
use App\Http\Requests\HouseRequest;
use App\Restriction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class HousesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except("show"); // if not auth, go to register
    }

    public function create()
    {
        return view('houses.create');
    }

    public function store(HouseRequest $request)
    {
        $data = $request->validated();
        $data['name'] = ucfirst($data['name']);
        $data['city'] = ucfirst($data['city']);
        $data['info'] = ucfirst($data['info']);

        $facilities=$this->makeTrueArray('facilities');
        $data['facility_id'] = Facility::create($facilities)->id;

        $restrictions=$this->makeTrueArray('restrictions');
        $data['restriction_id'] = Restriction::create($restrictions)->id;

        if (request('image')){
            $imagePath = $this->storeImage();
            $data['image']=$imagePath;
        }

        auth()->user()->houses()->create($data);

        return redirect(route('house.index'));
    }

    public function index()
    {
        $user_id = auth()->user()->id;
        $houses = House::where('user_id', '=', $user_id)->latest()->get();
        $bookings = Booking::join('houses', 'houses.id', '=', 'house_id')
            ->where('houses.user_id', '=', $user_id)
            ->where(function ($query){
                $query
                    ->where('status', '=', 1)
                    ->orWhereNull('status');
            })
            ->select('bookings.*')
            ->latest()->get();

        return view('houses.index', compact('houses', 'bookings'));
    }

    public function show(House $house)
    {
        $isBooked = auth()->user() ?
            $house->bookings()
            ->where('arrival', '=', session('arrival'))
            ->where('departure', '=', session('departure'))
            ->where('user_id', '=', auth()->user()->id)
            ->get()->isNotEmpty()
            : null;



//        $isFree = ! $house->bookings()
//            ->where(function ($query){
//                $query
//                    ->where('departure', '<', session('arrival'))
//                    ->orWhere('arrival', '>', session('departure'));
//            })
//            ->where(function ($query){
//                $query
//                    ->whereNull('status')
//                    ->orWhere('status', '=', 0);
//            })
//            ->get()->isEmpty();

//        dd($house->bookings()
//            ->where(function ($query){
//                $query
//                    ->where('departure', '>=', session('arrival'))
//                    ->orWhere('arrival', '<=', session('departure'));
//            })
//            ->get());

        return view('houses.show', compact('house', 'isBooked'));
    }

    public function destroy(House $house)
    {
        $house->delete();
        return redirect(route('house.index'));
    }

    public function edit(House $house)
    {
        $this->authorize('update', $house->user);
        return view('houses.edit', compact('house'));
    }

    public function update(House $house, HouseRequest $request)
    {
        $data = $request->validated();
        $data['name'] = ucfirst($data['name']);
        $data['city'] = ucfirst($data['city']);
        $data['info'] = ucfirst($data['info']);

        $facilities=$this->makeTrueArray('facilities');
        $house->facility->update($facilities);

        $restrictions=$this->makeTrueArray('restrictions');
        $house->restriction->update($restrictions);

        if (request('deleteImage')){
            $house->deleteImage();
        }
        elseif (request('image')){
            $house->deleteImage();
            $imagePath = $this->storeImage();
            $data['image']=$imagePath;
        }

        $house->update($data);

        return redirect("/house/{$house->id}");
    }


    // Private methods

    private function makeTrueArray($name)
    {
        $req = request($name);
        $arr=[];
        if ($req) foreach ($req as $value) $arr[$value]=true;
        return $arr;
    }

    private function storeImage()
    {
        $imagePath = request('image')->store('uploads', 'public');
        /*resize the image so that the largest side fits within the limit; the smaller
        side will be scaled to maintain the original aspect ratio*/
        $image = Image::make(public_path("storage/$imagePath"))
            ->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        $image->save();
        return $imagePath;
    }

}
