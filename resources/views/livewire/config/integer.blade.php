<div class="col-md-12">
    <div class="mb-3">
        <label class="form-label">{{ $v['name'] }} <span class="text-danger">*</span></label>
        <input type="number" wire:model="configs.{{ $k }}.value"
            class="form-control @error('configs.{{ $k }}.value') is-invalid @enderror"
            placeholder="{{ $v['name'] }}">
        @error('configs.{{ $k }}.value')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
