<div>
    @section('title', 'Payment Methods')

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
                            <a href="{{ url('masters/payment-methods/create') }}" class="btn btn-primary btn-sm"><i
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
                            <th class="sort" wire:click="sortOrder('ms_payment_methods.name')">Payment Method
                                {!! $sortLink !!}
                            </th>
                            <th class="w-px-150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentMethods as $pm)
                            <tr>
                                <td class="text-center">
                                    {{ ($paymentMethods->currentPage() - 1) * $paymentMethods->perPage() + $loop->index + 1 }}
                                </td>
                                <td class="border-start">{{ $pm->name }}</td>
                                <td class="border-start text-center">
                                    <button type="button" wire:click="edit('{{ $pm->id }}')"
                                        class="btn btn-xs btn-secondary me-2" title="Edit Data"><span
                                            class="bx bxs-edit"></span></button>
                                    <button type="button" wire:click="delete('{{ $pm->id }}')"
                                        class="btn btn-xs btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#PaymentMethodDeleteModal" title="Delete Data"><span
                                            class="bx bxs-trash"></span></button>
                                </td>
                            </tr>
                        @endforeach

                        @if ($paymentMethods->count() <= 0)
                            <tr>
                                <td colspan="3" class="text-center">No data..</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                {{ $paymentMethods->links('admin.custom-pagination-new') }}
            </div>
        </div>
    </div>

    {{-- Delete --}}
    <div wire:ignore.self class="modal fade" id="PaymentMethodDeleteModal" tabindex="-1" ruangan="dialog">
        <div class="modal-dialog" paymentmethod="document">
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
                $('#PaymentMethodDeleteModal').modal('hide');
            });
        </script>
    @endpush
</div>
