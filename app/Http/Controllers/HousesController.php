<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Facility;
use App\House;
use App\Http\Requests\ImageRequest;
use App\TemporaryImage;
use App\Http\Requests\HouseRequest;
use App\Restriction;
use ArrayObject;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use App\Libraries\House\Facades\HouseManager;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HousesController extends Controller
{
    /**
     * HousesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except("show");
    }

    /**
     * Open page for creating house
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $facilities = Facility::all();
        $restrictions = Restriction::all();

        return view('houses.create', compact('facilities', 'restrictions'));
    }

    /**
     * Create a new house
     *
     * @param HouseRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function store(HouseRequest $request)
    {
        $requestData = $request->all();
        if ($requestData['imgId']) {
            $requestData['image'] = getImgFromTempById($requestData['imgId']);
        }

        $user = Auth::user();
        $house = $user->houses()->create($requestData);

        HouseManager::attachToHouse($request->facilities, $request->restrictions, $house);

        return redirect(route('house.index'));
    }

    /**
     * Show user's houses and received bookings (except rejected)
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::id();
        $houses = House::where('user_id', '=', $userId)->latest()->get();
        $bookings = Booking::join('houses', 'houses.id', '=', 'house_id')
            ->where('houses.user_id', '=', $userId)
            ->where('status', '<>', Booking::STATUS_BOOKING_REJECT)
            ->select('bookings.*')
            ->with(['house', 'user'])
            ->latest()
            ->paginate(10);

        return view('houses.index', compact('houses', 'bookings'));
    }

    /**
     * Show one of the houses and check if it's booked and free
     *
     * @param House $house
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
                ->where('status', '<>', Booking::STATUS_BOOKING_CANCEL)
                ->exists()
            : null;

        if ($arrival) {
            $isFree = $house->isFree($arrival, $departure, $people);
        } else {
            $isFree = false;
        }

        $enoughPlaces = $house->places >= $people;

        return view('houses.show',
            compact('house', 'isBooked', 'isFree', 'enoughPlaces', 'arrival', 'departure', 'people'));
    }

    /**
     * Delete house
     *
     * @param House $house
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(House $house)
    {
        $house->delete();

        return redirect(route('house.index'));
    }

    /**
     * Show page for editing house
     *
     * @param House $house
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(House $house)
    {
        $this->authorize('update', $house->user);
        $facilities = Facility::all();
        $restrictions = Restriction::all();

        return view('houses.edit', compact('house', 'facilities', 'restrictions'));
    }

    /**
     * Update house
     *
     * @param House $house
     * @param HouseRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function update(House $house, HouseRequest $request)
    {
        $requestData = $request->all();

        if ($request->filled('deleteImage')) {
            $house->deleteImage();
        } elseif ($requestData['imgId']) {
            $requestData['image'] = getImgFromTempById($requestData['imgId']);
        }

        $house->update($requestData);

        HouseManager::attachToHouse($request->facilities, $request->restrictions, $house);

        return redirect(route('house.show', $house->id));
    }

    /**
     * Upload image by ajax
     *
     * @param Request $request
     * @return TemporaryImage
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['image'],
        ], [
            'file.image' => 'Вы выбрали не изображение',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()], 422);
        }

        $imgPath = storeImage($request->file);

        $user = Auth::user();
        $img = $user->temporaryImages()->create(['image' => $imgPath]);

        return $img;
    }
}
