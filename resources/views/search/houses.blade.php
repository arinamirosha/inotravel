<div class="row mt-3 justify-content-start">
    @forelse($houses as $house)

        <div class="mr-3 mb-4 col-5 col-md-3 col-lg-2">
            <div>
                <a href="{{ route('house.show', $house->id) }}">
                    <img src="{{ url($house->houseImage()) }}" alt="" class="w-100 rounded">
                </a>
            </div>
            <div>
                <a href="{{ route('house.show', $house->id) }}">{{ $house->name }}</a>
            </div>
            <div>
                {{ $house->city }}
            </div>
            <div>
                {{ $house->user->name }} {{ $house->user->surname }}
            </div>
        </div>

    @empty
        <div class="col-md-12 p-3 h5 text-center">
            {{ __('Not Found') }}
        </div>
    @endforelse
</div>

<div class="row offset-1">
    <div class="col-6">
        {{$houses->links()}}
    </div>
</div>
