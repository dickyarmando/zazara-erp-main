<div class="col-md-12">
    <div class="mb-3">
        <label class="form-label">{{ $v['name'] }} <span class="text-danger">*</span></label>
        <textarea wire:model="configs.{{ $k }}.value"
            class="form-control @error('configs.{{ $k }}.value') is-invalid @enderror"
            placeholder="{{ $v['name'] }}" rows="5"></textarea>
        @error('configs.{{ $k }}.value')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
