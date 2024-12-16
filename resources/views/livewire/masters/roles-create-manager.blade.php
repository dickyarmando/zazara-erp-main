<div>
    @section('title', 'Roles')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">@yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Masters</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/masters/roles') }}">@yield('title')</a>
                </li>
                <li class="breadcrumb-item active">{{ empty($set_id) ? 'Add New' : 'Edit' }}</li>
            </ol>
        </nav>
    </div>

    <x-flash-alert />

    <div class="card">
        <form wire:submit.prevent="store">
            <div class="card-header d-md-flex align-items-center justify-content-between">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name Role <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Name Role">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-offset-1 col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col col-sm-4 col-xs-12 d-flex">
                                                <h4 class="text-uppercase mb-0">Select Menus</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body table-responsive position-relative">
                                        <div wire:loading class="position-absolute fs-1 top-50 start-50 z-3 text-info">
                                            <i class="fa fa-spin fa-spinner"></i>
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Menus</th>
                                                    <th class="w-px-75">Show</th>
                                                    <th class="w-px-75">Create</th>
                                                    <th class="w-px-75">Update</th>
                                                    <th class="w-px-75">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($menus as $km => $vm)
                                                    <tr class="bg-info text-black">
                                                        <td>{{ $vm['name'] }}</td>
                                                        <td>
                                                            <input type="checkbox" class="form-check m-auto"
                                                                wire:model="menus.{{ $km }}.show"
                                                                value="1">
                                                        </td>
                                                        <td>
                                                            @if ($vm['is_create'] == '1')
                                                                <input type="checkbox" class="form-check m-auto"
                                                                    wire:model="menus.{{ $km }}.create"
                                                                    value="1">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($vm['is_update'] == '1')
                                                                <input type="checkbox" class="form-check m-auto"
                                                                    wire:model="menus.{{ $km }}.update"
                                                                    value="1">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($vm['is_delete'] == '1')
                                                                <input type="checkbox" class="form-check m-auto"
                                                                    wire:model="menus.{{ $km }}.delete"
                                                                    value="1">
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    @foreach ($vm['children'] as $ksm => $vsm)
                                                        <tr>
                                                            <td>{{ $vsm['name'] }}</td>
                                                            <td class="wid-5">
                                                                <input type="checkbox" class="form-check m-auto"
                                                                    wire:model="menus.{{ $km }}.children.{{ $ksm }}.show"
                                                                    value="1">
                                                            </td>
                                                            <td>
                                                                @if ($vsm['is_create'] == '1')
                                                                    <input type="checkbox" class="form-check m-auto"
                                                                        wire:model="menus.{{ $km }}.children.{{ $ksm }}.create"
                                                                        value="1"
                                                                        {{ $vsm['create'] === '1' ? 'checked' : 'checked="false"' }}>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($vsm['is_update'] == '1')
                                                                    <input type="checkbox" class="form-check m-auto"
                                                                        wire:model="menus.{{ $km }}.children.{{ $ksm }}.update"
                                                                        value="1">
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($vsm['is_delete'] == '1')
                                                                    <input type="checkbox" class="form-check m-auto"
                                                                        wire:model="menus.{{ $km }}.children.{{ $ksm }}.delete"
                                                                        value="1">
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-label-secondary" wire:click="backRedirect"><span
                        class="bx bx-arrow-back me-2"></span> Back</button>
                <button type="submit" class="btn btn-primary"><span class="bx bx-save me-2"></span> Save
                    Data</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script></script>
    @endpush
</div>
