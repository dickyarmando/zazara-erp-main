<div>
    @section('title', 'Invoice')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">@yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Sales</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/sales') }}">SO Tax</a>
                </li>
                <li class="breadcrumb-item active">{{ empty($set_id) ? 'Add New' : 'Edit' }} @yield('title')</li>
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
                                <label class="form-label">Invoice Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        id="basic-addon11">INV/ESB/{{ $month . $year }}/</span>
                                    <input type="text" class="form-control @error('number') is-invalid @enderror"
                                        wire:model="number" placeholder="Invoice Number" aria-label="Invoice Number"
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
                                <label class="form-label">SO Number</label>
                                <input type="text" wire:model="reference"
                                    class="form-control @error('reference') is-invalid @enderror"
                                    placeholder="Reference" readonly>
                                @error('reference')
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
                                    class="form-control @error('date') is-invalid @enderror" placeholder="Date">
                                @error('date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="customer_name"
                                    placeholder="-- Choose Customer --" readonly="">
                                @error('customer_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Termin <span class="text-danger">*</span></label>
                                <input type="number" wire:model="due_termin"
                                    class="form-control @error('due_termin') is-invalid @enderror" placeholder="Termin">
                                @error('due_termin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Uploaded Images</label>
                                @if ($salesFiles)
                                    <div class="mt-3">
                                        <ul>
                                            @foreach ($salesFiles as $file)
                                                <li>
                                                    {{ $file->file }} - <a
                                                        href="{{ url('/sales_files/' . $file->file) }}" target="_blank"
                                                        class="btn btn-xs btn-outline-primary"
                                                        title="View {{ $file->file }}">View</a>
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
                                            <th style="width:200px;">UoM</th>
                                            <th style="width:150px;">Qty</th>
                                            <th style="width:200px;">Rate</th>
                                            <th style="width:200px;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $index => $item)
                                            <tr>
                                                <td><input type="text" class="form-control"
                                                        wire:model="items.{{ $index }}.name" readonly></td>
                                                <td><input type="text" class="form-control"
                                                        wire:model="items.{{ $index }}.unit" readonly></td>
                                                <td><input type="text" class="form-control"
                                                        wire:model="items.{{ $index }}.qty" readonly>
                                                </td>
                                                <td><input type="text" class="form-control text-end"
                                                        wire:model="items.{{ $index }}.price" readonly>
                                                </td>
                                                <td><input type="text" class="form-control text-end"
                                                        wire:model="items.{{ $index }}.total" readonly></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center py-2" colspan="5">No items</td>
                                            </tr>
                                        @endforelse

                                        <tr>
                                            <td colspan="2">
                                                &nbsp;
                                            </td>
                                            <td colspan="2" class="text-right">Sub Total</td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="subtotal" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="2" class="text-right">PPN ({{ $ppn }} %)</td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="ppn_amount" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="2" class="text-right">Delivery Fee</td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="delivery_fee" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="2" class="text-right">Discount</td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="discount" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="2" class="text-right">Total</td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="total" readonly>
                                            </td>
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

    @push('scripts')
        <script></script>
    @endpush
</div>
