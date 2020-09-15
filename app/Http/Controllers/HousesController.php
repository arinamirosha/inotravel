<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Facility;
use App\House;
use App\Http\Requests\HouseRequest;
use App\Restriction;
use ArrayObject;
use Illuminate\Support\Facades\Auth;
use App\Libraries\House\Facades\HouseManager;
use Illuminate\Support\Facades\Cookie;

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

        if ($request->hasFile('image')){
            $imagePath = storeImage($request->image);
            $requestData['image'] = $imagePath;
        }

        $user = Auth::user();
        $house = $user->houses()->create($requestData);

        HouseManager::attachToHouse($request->facilities, $request->restrictions, $house);

        return redirect(route('house.index'));
    }

    public function index()
    {
        $user_id = Auth::id();
        $houses = House::where('user_id', '=', $user_id)->latest()->get();
        $bookings = Booking::join('houses', 'houses.id', '=', 'house_id')
            ->where('houses.user_id', '=', $user_id)
            ->where('status', '<>', Booking::STATUS_BOOKING_REJECT)
            ->select('bookings.*')
            ->with(['house', 'user'])
            ->latest()->get();

        return view('houses.index', compact('houses', 'bookings'));
    }

    public function show(House $house)
    {
        $house = $house->load(['user', 'facilities', 'restrictions']);

        $arrival = Cookie::get('arrival');
        $departure = Cookie::get('departure');
        $people = Cookie::get('people');

        $isBooked = Auth::check() ?
            $house->bookings()
            ->where('arrival', '=', $arrival)
            ->where('departure', '=', $departure)
            ->where('user_id', '=', Auth::id())
            ->exists()
            : null;

        $isFree = ! $house->bookings()
            ->where('status', '=', Booking::STATUS_BOOKING_ACCEPT)
            ->where(function ($query) use ($arrival, $departure) {
                $query
                    ->whereBetween('departure', [$arrival, $departure])
                    ->orWhereBetween('arrival', [$arrival, $departure]);
            })
            ->exists();

        $enoughPlaces = $house->places >= $people;

        return view('houses.show', compact('house', 'isBooked', 'isFree', 'enoughPlaces', 'arrival', 'departure', 'people'));
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
        $requestData = $request->all();

        if ($request->filled('deleteImage')){
            $house->deleteImage();
        }
        elseif ($request->hasFile('image')){
            $house->deleteImage();
            $imagePath = storeImage($request->image);
            $requestData['image'] = $imagePath;
        }

        $house->update($requestData);

        $house->facilities()->detach();
        $house->restrictions()->detach();

        HouseManager::attachToHouse($request->facilities, $request->restrictions, $house);

        return redirect(route('house.show', $house->id));
    }

}
