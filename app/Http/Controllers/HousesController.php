<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Facility;
use App\House;
use App\Http\Requests\HouseRequest;
use App\Restriction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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
        $requestData = $request->all();

        $facilities=$this->makeTrueArray($request->facilities);
        $requestData['facility_id'] = Facility::create($facilities)->id;

        $restrictions=$this->makeTrueArray($request->restrictions);
        $requestData['restriction_id'] = Restriction::create($restrictions)->id;

        if ($request->hasFile('image')){
            $imagePath = $this->storeImage($request->image);
            $requestData['image'] = $imagePath;
        }

        $user = Auth::user();
        $user->houses()->create($requestData);

        return redirect(route('house.index'));
    }

    public function index()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $houses = House::where('user_id', '=', $user_id)->latest()->get();
        $bookings = Booking::join('houses', 'houses.id', '=', 'house_id')
            ->where('houses.user_id', '=', $user_id)
            ->where('status', '<>', Booking::STATUS_BOOKING_REJECT)
            ->select('bookings.*')
            ->latest()->get();

        return view('houses.index', compact('houses', 'bookings'));
    }

    public function show(House $house)
    {
        $user = Auth::user();
        $isBooked = $user ?
            $house->bookings()
            ->where('arrival', '=', session('arrival'))
            ->where('departure', '=', session('departure'))
            ->where('user_id', '=', $user->id)
            ->get()->isNotEmpty()
            : null;

        $isFree = $house->bookings()
            ->where('status', '=', Booking::STATUS_BOOKING_ACCEPT)
            ->where(function ($query){
                $query
                    ->whereBetween('departure', [session('arrival'), session('departure')])
                    ->orWhereBetween('arrival', [session('arrival'), session('departure')]);
            })
            ->get()->isEmpty();

        return view('houses.show', compact('house', 'isBooked', 'isFree'));
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
        $facilities=$this->makeTrueFalseArray($request->facilities, 'facilities');
        $house->facility()->update($facilities);

        $restrictions=$this->makeTrueFalseArray($request->restrictions, 'restrictions');
        $house->restriction()->update($restrictions);

        $requestData = $request->all();

        if ($request->filled('deleteImage')){
            $house->deleteImage();
        }
        elseif ($request->hasFile('image')){
            $house->deleteImage();
            $imagePath = $this->storeImage($request->image);
            $requestData['image'] = $imagePath;
        }

        $house->update($requestData);

        return redirect(route('house.show', $house->id));
    }


    // Private methods

    private function makeTrueArray($req)
    {
        $arr=[];
        if ($req) foreach ($req as $value) $arr[$value]=true;
        return $arr;
    }

    private function makeTrueFalseArray($req, $tableName)
    {
        if (! $req) $req = [];
        $columns = Schema::getColumnListing($tableName);
        $arr=[];
        for ($i = 1; $i < count($columns) - 2; $i++) {
            $col_name = $columns[$i];
            $arr[$col_name] = in_array($col_name, $req) ? true : false;
        }
        return $arr;
    }

    private function storeImage($file)
    {
        $imagePath = $file->store('uploads', 'public');
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
