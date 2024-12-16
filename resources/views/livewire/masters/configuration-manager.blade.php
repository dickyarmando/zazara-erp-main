<div>
    <div>
        @section('title', 'Configuration')

        <div class="d-md-flex justify-content-between">
            <h2 class="mb-3"><span class="text-muted fw-light">@yield('title')</span></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Masters</a>
                    </li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </nav>
        </div>

        <x-flash-alert />

        <div class="card">
            <form wire:submit.prevent="store">
                <div class="card-header d-md-flex align-items-center justify-content-between">
                    <div class="col-md-12">
                        <div class="row">
                            @foreach ($configs as $k => $v)
                                @if ($v['type'] == 'text')
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">{{ $v['name'] }} <span
                                                    class="text-danger">*</span></label>
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
                                @else
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">{{ $v['name'] }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" wire:model="configs.{{ $k }}.value"
                                                class="form-control @error('configs.{{ $k }}.value') is-invalid @enderror"
                                                placeholder="{{ $v['name'] }}">
                                            @error('configs.{{ $k }}.value')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span class="bx bx-save me-2"></span> Save
                        Data</button>
                </div>
            </form>
        </div>

        @push('scripts')
            <script></script>
        @endpush
    </div>
</div>
