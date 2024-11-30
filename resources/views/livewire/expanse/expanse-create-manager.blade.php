<div>
    @section('title', 'Expanse')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">New @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">@yield('title')</a>
                </li>
                <li class="breadcrumb-item active">New @yield('title')</li>
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
                                <label class="form-label">Expanse Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        id="basic-addon11">EX/ESB/{{ $month . $year }}/</span>
                                    <input type="text" class="form-control @error('number') is-invalid @enderror"
                                        wire:model="number" placeholder="Expanse Number" aria-label="Expanse Number"
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
                        <div class="col-md-12">
                            <div class="mb-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Account</th>
                                            <th style="width:300px;">Amount</th>
                                            <th style="width:200px;">Type</th>
                                            <th style="width:80px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $index => $item)
                                            <tr>
                                                <td><input type="text" class="form-control"
                                                        wire:model="items.{{ $index }}.account_id"></td>
                                                <td><input type="text" class="form-control text-end"
                                                        wire:model="items.{{ $index }}.amount"
                                                        onclick="this.select();">
                                                </td>
                                                <td>
                                                    <select class="form-select"
                                                        wire:model="items.{{ $index }}.type">
                                                        <option value="db">Debit</option>
                                                        <option value="cr">Credit</option>
                                                    </select>
                                                </td>
                                                <td class="text-center"><button class="btn btn-sm btn-danger"
                                                        wire:click.prevent="remove('{{ $index }}')"><i
                                                            class="fa fa-times"></i></button></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center py-2" colspan="4">No items</td>
                                            </tr>
                                        @endforelse

                                        <tr>
                                            <td><button type="button" wire:click="add('cr')"
                                                    class="btn btn-primary btn-sm"><i class="fa fa-plus me-2"></i>Add
                                                    Line</button></td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="total_debit" readonly>
                                            </td>
                                            <td><input type="text" class="form-control text-end"
                                                    wire:model="total_credit" readonly>
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
                <button type="submit" class="btn btn-primary"><span class="bx bx-save me-2"></span> Save
                    Data</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('close-modal', event => {
                // $('#ChooseModalCustomers').modal('hide');
            });
        </script>
    @endpush
</div>
