<div>
    @section('title', 'Payment Methods')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">@yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Masters</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/masters/payment-methods') }}">@yield('title')</a>
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
                                <label class="form-label">Payment Methods <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Payment Methods">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Code of Account</label>
                                <input type="hidden" class="form-control @error('account_id') is-invalid @enderror"
                                    wire:model="account_id" readonly="">
                                <div class="input-group">
                                    <input type="text" class="form-control" wire:model="account_name"
                                        placeholder="-- Choose Code of Account --" readonly="">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#ChooseModalAccounts"><i class="fa fa-search"></i></button>
                                </div>
                                <div wire:ignore.self class="modal fade" id="ChooseModalAccounts"
                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Choose Code of Account</h5>
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
                                @error('account_id')
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
                <button type="submit" class="btn btn-primary"><span class="bx bx-save me-2"></span> Save Data</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('close-modal', event => {
                $('#ChooseModalAccounts').modal('hide');
            });
        </script>
    @endpush
</div>
