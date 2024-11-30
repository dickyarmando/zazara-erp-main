<div>
    @section('title', 'Roles')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
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

    <div class="col-md-offset-1 col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="row">
                    <div class="col col-sm-4 col-xs-12 d-flex">
                        <select class="form-select shadow-sm me-2 w-px-75" wire:model="perPage">
                            @foreach ([10, 25, 50, 100] as $val)
                                <option value="{{ $val }}" @if ($val == $perPage) selected @endif>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                        <input type="text" class="form-control w-100" placeholder="Search"
                            wire:model.debounce.500ms="searchKeyword">
                    </div>
                </div>
            </div>
            <div class="panel-body table-responsive position-relative">
                <div wire:loading class="position-absolute fs-1 top-50 start-50 z-3 text-info">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-px-75">No</th>
                            <th class="sort" wire:click="sortOrder('name')">Role Name {!! $sortLink !!}
                            </th>
                            <th class="w-px-150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td class="text-center">
                                    {{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->index + 1 }}</td>
                                <td class="border-start">{{ $role->name }}</td>
                                <td class="border-start text-center">
                                    <button type="button" class="btn btn-xs btn-secondary me-2" title="Edit User"><span
                                            class="bx bxs-edit"></span></button>
                                    <button type="button" class="btn btn-xs btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#UserDeleteModal" title="Delete Data"><span
                                            class="bx bxs-trash"></span></button>
                                </td>
                            </tr>
                        @endforeach

                        @if ($roles->count() <= 0)
                            <tr>
                                <td colspan="3" class="text-center">No data..</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                {{ $roles->links('admin.custom-pagination-new') }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script></script>
    @endpush
</div>
