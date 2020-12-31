<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="6000">
    <div class="toast-header">
        <strong class="mr-auto text-truncate">{{__('New User')}}</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">x</span>
        </button>
    </div>
    <a href="{{route('admin.index')}}" class="text-decoration-none text-secondary ">
        <div class="toast-body">
            {{$data['name']}} {{$data['surname']}}
        </div>
    </a>
</div>
