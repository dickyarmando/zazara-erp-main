<div>
    @section('title', 'Invoice')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Sales</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/sales') }}">SO Tax</a>
                </li>
                <li class="breadcrumb-item active">{{ $invoices->number }}</li>
            </ol>
        </nav>
    </div>

    <x-flash-alert />

    @if (isset($invoices->approved_at))
        <div class="alert alert-success alert-dismissible" role="alert">
            @inject('user', 'App\Models\User')
            @php
                $userApproved = $user->where('id', $invoices->approved_by)->first();
            @endphp
            Approved by {{ $userApproved->name }} at {{ $invoices->approved_at }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <button type="button" class="btn btn-label-secondary" wire:click="backRedirect"><span
                    class="bx bx-arrow-back me-2"></span> Back</button>
            @if (isset($invoices->approved_at))
                <button type="button" wire:click="printDocument" class="btn btn-primary"><span
                        class="bx bx-printer me-2"></span> Print</button>
            @else
                @if (isset($userRoles))
                    @if ($userRoles->is_approved == '1')
                        <button type="button" data-bs-toggle="modal" data-bs-target="#ApproveModal"
                            class="btn btn-success me-2"><span class="bx bx-check me-2"></span> Approve Invoice</button>
                    @endif
                @endif
            @endif
        </div>
        <div class="card-body">
            @include('livewire.invoice.data-view-invoice')
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
                    <p>Are you sure you want to approve invoice {{ $invoices->number }}?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="approve()" class="btn btn-success close-modal"
                        data-bs-dismiss="modal">Yes, approve</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('print', function() {
                var url = '{{ route('sales.invoice.view.print', ['id' => $invoices->id]) }}';
                window.open(url, '_blank');
            });

            window.addEventListener('close-modal', event => {
                $('#ApproveModal').modal('hide');
            });
        </script>
    @endpush
</div>
