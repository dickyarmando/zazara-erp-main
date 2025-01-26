<div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Reactive Input</h5>
        </div>
        <div class="card-body">
            <div class="py-4">
                <div class="mb-2">
                    <input type="text" class="form-control" wire:model="my_text" placeholder="Type something">
                </div>
                <div class="mb-2">
                    <input type="checkbox" id="red_color" wire:model="red_color" value="1"  {{ $red_color=='1' ? 'checked' : '' }}>
                    <label class="pointer" for="red_color">Red Color</label>
                </div>

                <div class="text-center {{ $red_color == '1' ? 'text-danger' : '' }}">
                    {{ $my_text=='' ? 'Your text will show here' : $my_text }}
                </div>
            </div>
        </div>
    </div>
</div>
