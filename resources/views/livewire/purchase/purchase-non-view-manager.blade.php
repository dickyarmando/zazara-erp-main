<div>
    @section('title', 'Purchase')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title') Non Tax</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">@yield('title')</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/purchase/non-tax') }}">PO Non Tax</a>
                </li>
                <li class="breadcrumb-item active">{{ $purchase->number }}</li>
            </ol>
        </nav>
    </div>

    <x-flash-alert />

    @if ($purchase->is_status == '0')
        <div class="alert alert-danger alert-dismissible" role="alert">
            @inject('user', 'App\Models\User')
            @php
                $userCanceled = $user->where('id', $purchase->deleted_by)->first();
            @endphp
            Canceled by {{ $userCanceled->name }} at {{ $purchase->deleted_at }}
        </div>
    @else
        @if (isset($purchase->approved_at))
            <div class="alert alert-success alert-dismissible" role="alert">
                @inject('user', 'App\Models\User')
                @php
                    $userApproved = $user->where('id', $purchase->approved_by)->first();
                @endphp
                Approved by {{ $userApproved->name }} at {{ $purchase->approved_at }}
            </div>
        @endif
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <button type="button" class="btn btn-label-secondary" wire:click="backRedirect"><span
                    class="bx bx-arrow-back me-2"></span> Back</button>
            <div>
                @if ($purchase->is_status == '1')
                    <button type="button" wire:click="printDocument" class="btn btn-primary"><span
                            class="bx bx-printer me-2"></span> Print</button>
                @endif
                @if ($userRoles->is_approved === '1' && $purchase->approved_at == null)
                    <button type="button" data-bs-toggle="modal" data-bs-target="#ApproveModal"
                        class="btn btn-success"><span class="bx bx-check me-2"></span> Approve Purchase</button>
                @endif
                @if (
                    $purchase->approved_at != null &&
                        $purchase->is_status == '1' &&
                        $purchase->is_payed == '0' &&
                        $purchase->is_posting == '0')
                    <button type="button" data-bs-toggle="modal" data-bs-target="#CancelModal"
                        class="btn btn-danger"><span class="bx bx-x me-2"></span> Cancel Purchase</button>
                @endif
            </div>
        </div>
        <div class="card-body" id="print-document">
            @include('livewire.purchase.data-view-purchase-non')
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
                    <p>Are you sure you want to approve purchase {{ $purchase->number }}?</p>
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
        <div class="modal-dialog" product="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Cancel</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this purchase?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="destroy()" class="btn btn-danger close-modal"
                        data-bs-dismiss="modal">Yes, Cancel</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('print', function() {
                var url = '{{ route('purchase.non.view.print', ['id' => $purchase->id]) }}';
                window.open(url, '_blank');
            });

            window.addEventListener('close-modal', event => {
                $('#ApproveModal').modal('hide');
                $('#CancelModal').modal('hide');
            });
        </script>
    @endpush
</div>
