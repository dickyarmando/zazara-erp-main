<div>
    @auth
    <div class="card animate__animated animate__backInLeft">
        {{-- <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Avatar</h5>
        </div> --}}
        <div class="card-body py-3 px-3 d-md-flex justify-content-between">
            <div class="d-flex justify-content-left">
                <div class="me-3">
                @empty(auth()->user()->avatar)
                    <img src="{{ asset('avatar/default.png') }}" alt="" class="w-auto h-px-75 rounded-circle" />
                @else
                    <img src="{{ asset('avatar/'.auth()->user()->avatar) }}" alt="" class="w-auto h-px-75 rounded-circle">
                @endempty
                </div>
                <div class="">
                    <p class="fs-4 mb-1">Welcome, <b>{{ auth()->user()->name }}</b></p>
                    <p><a href="{{ url('livewire') }}">Change Profile</a></p>
                </div>
            </div>
            <div>
                <button class="btn btn-outline-primary mt-3" onclick="document.location.href='{{ url('logout') }}'"><i class="fa fa-arrow-right-from-bracket me-2"></i>Log Out</button>
            </div>

            {{-- <div class="mb-3">
                <input type="file" wire:model="avatar" class="form-control @error('avatar') is-invalid @enderror" id="avatar_{{ rand(100,999) }}" />
                <div wire:loading wire:target="avatar">Uploading...</div>
                @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}

        </div>
    </div>
    @endauth
</div>
