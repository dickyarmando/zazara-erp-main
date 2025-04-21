<div class="col-md-12">
    <div class="mb-3">
        @inject('msAccounts', 'App\Models\MsAccount')
        <label class="form-label">{{ $v['name'] }}</label>
        <select wire:model="configs.{{ $k }}.value"
            class="form-select @error('configs.{{ $k }}.value') is-invalid @enderror">
            <option value="">-- Choose Account --</option>
            @foreach ($msAccounts::where('is_status', '=', '1')->orderBy('code')->select('id', 'code', 'name')->get() as $key => $val)
                <option value="{{ $val['id'] }}">
                    {{ $val['code'] }} - {{ $val['name'] }}</option>
            @endforeach
        </select>
        @error('configs.{{ $k }}.value')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
