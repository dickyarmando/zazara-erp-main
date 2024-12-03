<div>
    @section('title', 'Purchase')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">@yield('title') Non Tax</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">@yield('title')</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/purchase/non-tax') }}">PO Non Tax</a>
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Order Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        id="basic-addon11">PO/ESB-N/{{ $month . $year }}/</span>
                                    <input type="text" class="form-control @error('number') is-invalid @enderror"
                                        wire:model="number" placeholder="Order Number" aria-label="Order Number"
                                        aria-describedby="basic-addon11" {{ empty($set_id) ? '' : 'readonly' }} />
                                </div>
                                @error('number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" wire:model="date"
                                    class="form-control @error('date') is-invalid @enderror" placeholder="Date"
                                    {{ empty($set_id) ? '' : 'readonly' }}>
                                @error('date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Supplier <span class="text-danger">*</span></label>
                                <input type="hidden" class="form-control @error('supplier_id') is-invalid @enderror"
                                    wire:model="supplier_id" readonly="">
                                <div class="input-group">
                                    <input type="text" class="form-control" wire:model="supplier_name"
                                        placeholder="-- Choose Supplier --" readonly="">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#ChooseModalSuppliers"><i class="fa fa-search"></i></button>
                                </div>
                                <div wire:ignore.self class="modal fade" id="ChooseModalSuppliers"
                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Choose Supplier</h5>
                                                <button type="button" class="btn-close"
                                                    wire:click="closeModal"></button>
                                            </div>
                                            <div class="modal-body position-relative">

                                                <div class="d-md-flex align-items-center justify-content-end mb-3">
                                                    <input type="text" class="form-control shadow-sm"
                                                        placeholder="Search" style="width: 250px;"
                                                        wire:model="searchKeyword">
                                                </div>
                                                <div wire:loading
                                                    class="position-absolute fs-1 top-50 start-50 z-3 text-info">
                                                    <i class="fa fa-spin fa-spinner"></i>
                                                </div>
                                                <table
                                                    class="table card-table table-hover table-striped table-sm table-bordered">
                                                    <thead>
                                                        <tr class="border-top">
                                                            <th class="w-px-75">No</th>
                                                            <th class="sort" wire:click="sortOrder('code')">Code
                                                                {!! $sortLink !!}</th>
                                                            <th class="sort" wire:click="sortOrder('company_name')">
                                                                Company
                                                                Name
                                                                {!! $sortLink !!}</th>
                                                            <th class="sort" wire:click="sortOrder('name')">Contact
                                                                Person
                                                                {!! $sortLink !!}</th>
                                                            <th class="sort" wire:click="sortOrder('phone')">Phone
                                                                {!! $sortLink !!}</th>
                                                            <th class="w-px-150">#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($suppliers as $supplier)
                                                            <tr>
                                                                <td class="text-center">
                                                                    {{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->index + 1 }}
                                                                </td>
                                                                <td class="text-center">{{ $supplier->code }}</td>
                                                                <td>{{ $supplier->company_name }}</td>
                                                                <td>{{ $supplier->name }}</td>
                                                                <td>{{ $supplier->phone }}</td>
                                                                <td><button class="btn btn-xs btn-outline-danger w-100"
                                                                        wire:click.prevent="chooseSupplier('{{ $supplier->id }}')"><i
                                                                            class="fa fa-plus me-2"></i> Select</button>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="text-center py-2" colspan="6">No Suppliers
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    {{ $suppliers->links('admin.custom-pagination') }}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('supplier_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Reference</label>
                                <input type="text" wire:model="reference"
                                    class="form-control @error('reference') is-invalid @enderror"
                                    placeholder="Reference">
                                @error('reference')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Images</label>
                                <input type="file" wire:model="files"
                                    class="form-control @error('files.*') is-invalid @enderror" id="formFileMultiple"
                                    multiple>
                                <div wire:loading wire:target="files">Checking...</div>
                                @error('files.*')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if ($files)
                                    <div class="mt-3">
                                        <h5>Selected Files:</h5>
                                        <ul>
                                            @foreach ($files as $file)
                                                <li>{{ $file->getClientOriginalName() }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if ($purchaseFiles)
                                    <div class="mt-3">
                                        <h5>Uploaded Files:</h5>
                                        <ul>
                                            @foreach ($purchaseFiles as $file)
                                                <li>
                                                    {{ $file->file }} - <a
                                                        href="{{ url('/purchase_non_files/' . $file->file) }}"
                                                        target="_blank" class="btn btn-xs btn-outline-primary"
                                                        title="View {{ $file->file }}">View</a> - <button
                                                        type="button" wire:click="deleteFile('{{ $file->id }}')"
                                                        class="btn btn-xs btn-outline-danger" data-bs-toggle="modal"
                                                        data-bs-target="#FileDeleteModal"
                                                        title="Delete File">Delete</button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th style="width:200px;">Unit</th>
                                            <th style="width:150px;">Qty</th>
                                            <th style="width:200px;">Rate</th>
                                            <th style="width:200px;">Amount</th>
                                            <th style="width:80px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $index => $item)
                                            <tr>
                                                <td><input type="text" class="form-control"
                                                        wire:model="items.{{ $index }}.name"></td>
                                                <td><input type="text" class="form-control"
                                                        wire:model="items.{{ $index }}.unit"></td>
                                                <td><input type="text" class="form-control"
                                                        wire:model="items.{{ $index }}.qty"
                                                        wire:blur.debounce.250ms="calculate({{ $index }})"
                                                        onclick="this.select();">
                                                </td>
                                                <td><input type="text" class="form-control text-end"
                                                        wire:model="items.{{ $index }}.price"
                                                        wire:blur.debounce.250ms="calculate({{ $index }})"
                                                        onclick="this.select();">
                                                </td>
                                                <td><input type="text" class="form-control text-end"
                                                        wire:model="items.{{ $index }}.total" readonly></td>
                                                <td class="text-center"><button class="btn btn-sm btn-danger"
                                                        wire:click.prevent="remove('{{ $index }}')"><i
                                                            class="fa fa-times"></i></button></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center py-2" colspan="6">No items</td>
                                            </tr>
                                        @endforelse

                                        <tr>
                                            <td colspan="2"><button type="button" wire:click="add"
                                                    class="btn btn-primary btn-sm"><i class="fa fa-plus me-2"></i>Add
                                                    Line</button></td>
                                            <td colspan="2" class="text-right">Sub Total</td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="subtotal" readonly>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="2" class="text-right">Delivery Fee</td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="delivery_fee"
                                                    wire:blur.debounce.250ms="calculateTotal()"
                                                    onclick="this.select();">
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="2" class="text-right">Discount</td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="discount" wire:blur.debounce.250ms="calculateTotal()"
                                                    onclick="this.select();">
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="2" class="text-right">Total</td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="total" readonly>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Summary / Note</label>
                                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="Summary / Note"
                                    rows="5"></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
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

    {{-- Delete --}}
    <div wire:ignore.self class="modal fade" id="FileDeleteModal" tabindex="-1" product="dialog">
        <div class="modal-dialog" file="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete file?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="destroyFile()" class="btn btn-danger close-modal"
                        data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('close-modal', event => {
                $('#ChooseModalSuppliers').modal('hide');
                $('#FileDeleteModal').modal('hide');
            });
        </script>
    @endpush
</div>
