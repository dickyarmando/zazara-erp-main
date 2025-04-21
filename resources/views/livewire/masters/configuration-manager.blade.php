<div>
    <div>
        @section('title', 'Configuration')

        <div class="d-md-flex justify-content-between">
            <h2 class="mb-3"><span class="text-muted fw-light">@yield('title')</span></h2>
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

        @livewire('config.general-config-component')
        @livewire('config.po-config-component')
        @livewire('config.so-config-component')
        @livewire('config.invoice-config-component')
        @livewire('config.account-purchase-config-component')
        @livewire('config.account-sales-config-component')

        @push('scripts')
            <script></script>
        @endpush
    </div>
</div>
