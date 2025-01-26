<div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Avatar</h5>
        </div>
        <div class="card-body text-center">
            <x-flash-alert />
            <form class="" wire:submit.prevent="save">
            <div class="mb-3">
                @empty(auth()->user()->avatar)
                    <img src="{{ asset('avatar/default.png') }}" alt="" class="w-auto h-px-150 rounded-circle" />
                @else
                    <img src="{{ asset('avatar/'.auth()->user()->avatar) }}" alt="" class="w-auto h-px-150 rounded-circle">
                @endempty
            </div>
            <div class="mb-3">
                <input type="file" wire:model="avatar" class="form-control @error('avatar') is-invalid @enderror" id="avatar_{{ rand(100,999) }}" />
                <div wire:loading wire:target="avatar">Uploading...</div>
                @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            </form>
        </div>
    </div>
</div>
