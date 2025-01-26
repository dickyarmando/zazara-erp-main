<div>
    <x-flash-alert />

    <div class="card mb-4">
        <form wire:submit.prevent="store">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Sales Order Configuration</h5>
            </div>
            <div class="card-body d-md-flex align-items-center justify-content-between">
                <div class="col-md-12">
                    <div class="row">
                        @include('livewire.config.content')
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><span class="bx bx-save me-2"></span> Save
                    Data</button>
            </div>
        </form>
    </div>
</div>
