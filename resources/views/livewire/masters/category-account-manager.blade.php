<div>
    @section('title', 'Category Accounts')

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
                    <div class="col-sm-8 col-xs-12 text-right">
                        <div class="d-md-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#CategoryModal"><i class="bx bx-plus me-2"></i>Add New</button>
                        </div>
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
                            <th class="sort" wire:click="sortOrder('name')">Category Account Name
                                {!! $sortLink !!}
                            </th>
                            <th class="w-px-150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="text-center">
                                    {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->index + 1 }}
                                </td>
                                <td class="border-start">{{ $category->name }}</td>
                                <td class="border-start text-center">
                                    <button type="button" wire:click="edit('{{ $category->id }}')"
                                        class="btn btn-xs btn-secondary me-2" data-bs-toggle="modal"
                                        data-bs-target="#CategoryModal" title="Edit Data"><span
                                            class="bx bxs-edit"></span></button>
                                    <button type="button" wire:click="delete('{{ $category->id }}')"
                                        class="btn btn-xs btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#CategoryDeleteModal" title="Delete Data"><span
                                            class="bx bxs-trash"></span></button>
                                </td>
                            </tr>
                        @endforeach

                        @if ($categories->count() <= 0)
                            <tr>
                                <td colspan="3" class="text-center">No data..</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                {{ $categories->links('admin.custom-pagination-new') }}
            </div>
        </div>
    </div>

    {{-- Edit --}}
    <div wire:ignore.self class="modal fade" id="CategoryModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" category="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ empty($set_id) ? 'Add New' : 'Edit' }} @yield('title')</h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="modal-body">

                        <x-flash-alert />

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" wire:model="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Name">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete --}}
    <div wire:ignore.self class="modal fade" id="CategoryDeleteModal" tabindex="-1" product="dialog">
        <div class="modal-dialog" category="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="destroy()" class="btn btn-danger close-modal"
                        data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('close-modal', event => {
                $('#CategoryModal').modal('hide');
                $('#CategoryDeleteModal').modal('hide');
            });
        </script>
    @endpush
</div>
