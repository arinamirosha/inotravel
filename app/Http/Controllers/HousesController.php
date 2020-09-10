<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Facility;
use App\House;
use App\Http\Requests\HouseRequest;
use App\Restriction;
use Illuminate\Support\Facades\Auth;
use App\Libraries\House\Facades\HouseManager;

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

        $facilities = HouseManager::makeTrueArray($request->facilities);
        $requestData['facility_id'] = Facility::create($facilities)->id;

        $restrictions = HouseManager::makeTrueArray($request->restrictions);
        $requestData['restriction_id'] = Restriction::create($restrictions)->id;

        if ($request->hasFile('image')){
            $imagePath = HouseManager::storeImage($request->image);
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
        $facilities=HouseManager::makeTrueFalseArray($request->facilities, 'facilities');
        $house->facility()->update($facilities);

        $restrictions=HouseManager::makeTrueFalseArray($request->restrictions, 'restrictions');
        $house->restriction()->update($restrictions);

        $requestData = $request->all();

        if ($request->filled('deleteImage')){
            $house->deleteImage();
        }
        elseif ($request->hasFile('image')){
            $house->deleteImage();
            $imagePath = HouseManager::storeImage($request->image);
            $requestData['image'] = $imagePath;
        }

        $house->update($requestData);

        return redirect(route('house.show', $house->id));
    }

}
