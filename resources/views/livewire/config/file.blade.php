<div class="col-md-12">
    <div class="mb-3">
        <label class="form-label">{{ $v['name'] }} <span class="text-danger">*</span></label>
        <input type="file" wire:model="configs.{{ $k }}.file"
            class="form-control @error('configs.{{ $k }}.file') is-invalid @enderror">
        <div wire:loading wire:target="configs.{{ $k }}.file">Checking...</div>
        @error('configs.{{ $k }}.file')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        @if (isset($configs[$k]['file']))
            <div class="mt-3">
                <h5>Selected Image:</h5>
                <img src="{{ $configs[$k]['file']->temporaryUrl() }}" alt="Preview Image" class="img-fluid"
                    style="max-width: 300px;">
            </div>
        @else
            @if (isset($configs[$k]['value']))
                <div class="mt-3">
                    <h5>Uploaded Image:</h5>
                    <img src="{{ asset('assets/img/config/' . $configs[$k]['value']) }}" alt="Preview Image"
                        class="img-fluid" style="max-width: 300px;">
                </div>
            @endif
        @endif
    </div>
</div>
