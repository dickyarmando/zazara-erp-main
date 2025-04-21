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
                                            <th style="width:300px;">Debit</th>
                                            <th style="width:300px;">Credit</th>
                                            <th style="width:80px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $index => $item)
                                            <tr>
                                                <td>
                                                    <input type="hidden" class="form-control"
                                                        wire:model="items.{{ $index }}.account_id">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            wire:model="items.{{ $index }}.account_name"
                                                            placeholder="-- Choose Code of Account --" readonly="">
                                                        <button type="button" class="btn btn-outline-primary"
                                                            data-bs-toggle="modal" data-bs-target="#ChooseModalAccounts"
                                                            wire:click.prevent="setIndex('{{ $index }}')"><i
                                                                class="fa fa-search"></i></button>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control text-end"
                                                        wire:model="items.{{ $index }}.debit"
                                                        onclick="this.select();" wire:blur="calculateTotal">
                                                </td>
                                                <td><input type="text" class="form-control text-end"
                                                        wire:model="items.{{ $index }}.credit"
                                                        onclick="this.select();" wire:blur="calculateTotal">
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
                                            <td>
                                                <span>Total Debit</span>
                                                <input type="text" class="form-control text-end"
                                                    wire:model="total_debit" readonly>
                                            </td>
                                            <td>
                                                <span>Total Credit</span>
                                                <input type="text" class="form-control text-end"
                                                    wire:model="total_credit" readonly>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <span class="text-danger" id="notifBalance">The total amount of debits and total credits
                                    must
                                    be
                                    balanced</span>
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
                <button type="submit" id="btnSave" class="btn btn-primary"><span class="bx bx-save me-2"></span>
                    Save
                    Data</button>
            </div>
        </form>
    </div>

    <div wire:ignore.self class="modal fade" id="ChooseModalAccounts" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose Code of Account</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body position-relative">

                    <div class="d-md-flex align-items-center justify-content-end mb-3">
                        <input type="text" class="form-control shadow-sm" placeholder="Search"
                            style="width: 250px;" wire:model="searchKeyword">
                    </div>
                    <div wire:loading class="position-absolute fs-1 top-50 start-50 z-3 text-info">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                    <table class="table card-table table-hover table-striped table-sm table-bordered">
                        <thead>
                            <tr class="border-top">
                                <th class="w-px-75">No</th>
                                <th class="sort" wire:click="sortOrder('code')">Code of
                                    Account
                                    {!! $sortLink !!}</th>
                                <th class="sort" wire:click="sortOrder('company_name')">
                                    Name Account
                                    {!! $sortLink !!}</th>
                                <th class="sort" wire:click="sortOrder('name')">Category
                                    {!! $sortLink !!}</th>
                                <th class="sort" wire:click="sortOrder('phone')">Type
                                    {!! $sortLink !!}</th>
                                <th class="w-px-150">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($accounts as $account)
                                <tr>
                                    <td class="text-center">
                                        {{ ($accounts->currentPage() - 1) * $accounts->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td class="text-center">{{ $account->code }}</td>
                                    <td>{{ $account->name }}</td>
                                    <td>{{ $account->category_account_name }}</td>
                                    <td>
                                        @if ($account->account_type == 'db')
                                            Debit
                                        @elseif($account->account_type == 'cr')
                                            Credit
                                        @endif
                                    </td>
                                    <td><button class="btn btn-xs btn-outline-danger w-100"
                                            wire:click.prevent="chooseAccount('{{ $account->id }}')"><i
                                                class="fa fa-plus me-2"></i> Select</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-2" colspan="6">No Accounts
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $accounts->links('admin.custom-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#notifBalance').hide();
            });

            window.addEventListener('close-modal', event => {
                $('#ChooseModalAccounts').modal('hide');
            });

            window.addEventListener('balance', event => {
                $('#notifBalance').hide();
                $('#btnSave').prop("disabled", false);
            });

            window.addEventListener('unbalance', event => {
                $('#notifBalance').show();
                $('#btnSave').prop("disabled", true);
            })

            window.addEventListener('error', event => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: event.detail.message,
                    timer: 1500
                });
            })
        </script>
    @endpush
</div>
