<div>
    @section('title', 'Sales')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title') Non Tax</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">@yield('title')</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/sales') }}">SO Non Tax</a>
                </li>
                <li class="breadcrumb-item active">{{ $sales->number }}</li>
            </ol>
        </nav>
    </div>

    <x-flash-alert />

    @if (isset($sales->approved_at))
        <div class="alert alert-success alert-dismissible" role="alert">
            @inject('user', 'App\Models\User')
            @php
                $userApproved = $user->where('id', $sales->approved_by)->first();
            @endphp
            Approved by {{ $userApproved->name }} at {{ $sales->approved_at }}
        </div>
    @endif

    @if ($sales->is_status == '0')
        <div class="alert alert-danger alert-dismissible" role="alert">
            @inject('user', 'App\Models\User')
            @php
                $userCanceled = $user->where('id', $sales->updated_by)->first();
            @endphp
            Canceled by {{ $userCanceled->name }} at {{ $sales->updated_at }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <button type="button" class="btn btn-label-secondary" wire:click="backRedirect"><span
                    class="bx bx-arrow-back me-2"></span> Back</button>
            @if (isset($sales->approved_at))
                <div class="d-flex">
                    <button type="button" wire:click="printDocument" class="btn btn-primary me-2"><span
                            class="bx bx-printer me-2"></span> Print</button>
                    @if ($user_role == '1' && $sales->is_invoice == '0')
                        <button type="button" data-bs-toggle="modal" data-bs-target="#UnapprovedModal"
                            class="btn btn-warning me-2"><span class="bx bx-refresh me-2"></span> Unapproved
                            Sales</button>
                    @endif
                </div>
            @else
                @if ($userRoles->is_approved === '1' && $sales->is_status == '1')
                    <div class="d-flex">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#ApproveModal"
                            class="btn btn-success me-2"><span class="bx bx-check me-2"></span> Approve Sales</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#CancelModal"
                            class="btn btn-danger"><span class="bx bx-x me-2"></span> Cancel Sales</button>
                    </div>
                @endif
            @endif
        </div>
        <div class="card-body">
            @include('livewire.sales.data-view-sales-non')
        </div>
    </div>

    {{-- Approve --}}
    <div wire:ignore.self class="modal fade" id="ApproveModal" tabindex="-1" product="dialog">
        <div class="modal-dialog" approve="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Approve</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve sales {{ $sales->number }}?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="approve()" class="btn btn-success close-modal"
                        data-bs-dismiss="modal">Yes, approve</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancel --}}
    <div wire:ignore.self class="modal fade" id="CancelModal" tabindex="-1" product="dialog">
        <div class="modal-dialog" approve="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Cancel</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel sales {{ $sales->number }}?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="cancelSales()" class="btn btn-danger close-modal"
                        data-bs-dismiss="modal">Yes, cancel</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Unapproved --}}
    <div wire:ignore.self class="modal fade" id="UnapprovedModal" tabindex="-1" product="dialog">
        <div class="modal-dialog" unapproved="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Unapproved</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to unapproved sales {{ $sales->number }}?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="unapproved()" class="btn btn-warning close-modal"
                        data-bs-dismiss="modal">Yes, unapproved</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('print', function() {
                var url = '{{ route('sales.non.view.print', ['id' => $sales->id]) }}';
                window.open(url, '_blank');
            });

            window.addEventListener('close-modal', event => {
                $('#ApproveModal').modal('hide');
                $('#CancelModal').modal('hide');
                $('#UnapprovedModal').modal('hide');
            });
        </script>
    @endpush
</div>
