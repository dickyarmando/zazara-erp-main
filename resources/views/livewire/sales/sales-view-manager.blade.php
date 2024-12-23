<div>
    @section('title', 'Sales')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">@yield('title')</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/sales') }}">SO Tax</a>
                </li>
                <li class="breadcrumb-item active">{{ $sales->number }}</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <button type="button" class="btn btn-label-secondary" wire:click="backRedirect"><span
                    class="bx bx-arrow-back me-2"></span> Back</button>
            <button type="button" wire:click="printDocument" class="btn btn-primary"><span
                    class="bx bx-printer me-2"></span> Print</button>
        </div>
        <div class="card-body">
            @include('livewire.sales.data-view-sales')
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('print', function() {
                var url = '{{ route('sales.view.print', ['id' => $sales->id]) }}';
                window.open(url, '_blank');
            });
        </script>
    @endpush
</div>
