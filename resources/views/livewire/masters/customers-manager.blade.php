<div>
    @section('title', 'Customers')

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
                            <a href="{{ url('masters/customers/create') }}" class="btn btn-primary btn-sm"><i
                                    class="bx bx-plus me-2"></i>Add New</a>
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
                            <th class="sort" wire:click="sortOrder('ms_customers.code')">Code {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('ms_customers.company_name')">Company Name
                                {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('ms_customers.email')">Email
                                {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('ms_customers.phone')">Phone
                                {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('ms_customers.telephone')">Telephone
                                {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('ms_customers.name')">Contact Person
                                {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('ms_customers.name')">Status
                                {!! $sortLink !!}</th>
                            <th class="w-px-150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td class="text-center">
                                    {{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->index + 1 }}
                                </td>
                                <td class="border-start">{{ $customer->code }}</td>
                                <td class="border-start">{{ $customer->company_name }}</td>
                                <td class="border-start">{{ $customer->email }}</td>
                                <td class="border-start">{{ $customer->phone }}</td>
                                <td class="border-start">{{ $customer->telephone }}</td>
                                <td class="border-start">{{ $customer->name }}</td>
                                <td class="border-start text-center">
                                    @if ($customer->is_status == '1')
                                        <span class="badge bg-label-success" text-capitalized> Active </span>
                                    @else
                                        <span class="badge bg-label-danger" text-capitalized> Non Active </span>
                                    @endif
                                </td>
                                <td class="border-start text-center">
                                    <button type="button" wire:click="edit('{{ $customer->id }}')"
                                        class="btn btn-xs btn-secondary me-2" title="Edit Data"><span
                                            class="bx bxs-edit"></span></button>
                                    @if ($customer->is_status == '1')
                                        <button type="button" wire:click="delete('{{ $customer->id }}')"
                                            class="btn btn-xs btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#CustomerDeleteModal" title="Non Active Data"><span
                                                class="bx bx-x"></span></button>
                                    @else
                                        <button type="button" wire:click="delete('{{ $customer->id }}')"
                                            class="btn btn-xs btn-success" data-bs-toggle="modal"
                                            data-bs-target="#CustomerActivedModal" title="Actived Data"><span
                                                class="bx bx-check"></span></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if ($customers->count() <= 0)
                            <tr>
                                <td colspan="8" class="text-center">No data..</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                {{ $customers->links('admin.custom-pagination-new') }}
            </div>
        </div>
    </div>

    {{-- Non Active --}}
    <div wire:ignore.self class="modal fade" id="CustomerDeleteModal" tabindex="-1" ruangan="dialog">
        <div class="modal-dialog" customer="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Non Active</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to non active?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="destroy()" class="btn btn-danger close-modal"
                        data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Active --}}
    <div wire:ignore.self class="modal fade" id="CustomerActivedModal" tabindex="-1" ruangan="dialog">
        <div class="modal-dialog" customer="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Actived</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to actived?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="actived()" class="btn btn-success close-modal"
                        data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('close-modal', event => {
                $('#CustomerDeleteModal').modal('hide');
                $('#CustomerActivedModal').modal('hide');
            });
        </script>
    @endpush
</div>
