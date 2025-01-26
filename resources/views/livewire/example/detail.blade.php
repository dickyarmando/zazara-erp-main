<div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Detail Input</h5>
            <button wire:click="add" class="btn btn-primary btn-sm"><i class="fa fa-plus me-2"></i>Add</button>
        </div>
        <div class="card-body">

            <table class="table">
            <thead>
            <tr>
                <th>Item</th>
                <th style="width:150px;">Qty</th>
                <th style="width:80px;" class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ( $items as $index => $item )
            <tr>
                <td>
                    <div class="input-group">
                        <input type="text" class="form-control" wire:model="items.{{ $index }}.name" readonly="">
                        <button class="btn btn-outline-primary" wire:click.prevent="setIndex('{{ $index }}')" data-bs-toggle="modal" data-bs-target="#ChooseModal"><i class="fa fa-search"></i></button>
                    </div>
                </td>
                <td><input type="text" class="form-control" wire:model="items.{{ $index }}.qty"></td>
                <td><button class="btn btn-sm btn-danger" wire:click.prevent="remove('{{ $index }}')"><i class="fa fa-times"></i></button></td>
            </tr>
            @empty
            <tr>
                <td class="text-center py-2" colspan="3">No items</td>
            </tr>
            @endforelse
            </tbody>
            </table>

        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="ChooseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Choose User</h5>
            <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>
            <div class="modal-body position-relative">

                <div class="d-md-flex align-items-center justify-content-end mb-3">
                    <input type="text" class="form-control shadow-sm" placeholder="Search" style="width: 250px;" wire:model="searchKeyword" >
                </div>
                <div wire:loading class="position-absolute fs-1 top-50 start-50 z-3 text-info">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <table class="table card-table table-hover table-striped table-sm table-bordered">
                <thead>
                <tr class="border-top">
                    <th class="w-px-75">No</th>
                    <th style="width:40%;" class="sort" wire:click="sortOrder('name')">Name {!! $sortLink !!}</th>
                    <th class="sort" wire:click="sortOrder('email')">Email {!! $sortLink !!}</th>
                    <th class="w-px-150">#</th>
                </tr>
                </thead>
                </thead>
                <tbody>
                @forelse ( $users as $user )
                <tr>
                    <td>{{ ($users->currentPage()-1) * $users->perPage() + $loop->index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><button class="btn btn-xs btn-outline-danger w-100" wire:click.prevent="choose('{{ $user->id }}')"><i class="fa fa-plus me-2"></i> Select</button></td>
                </tr>
                @empty
                <tr>
                    <td class="text-center py-2" colspan="3">No items</td>
                </tr>
                @endforelse

                @for($i=1; $i<=($users->perPage()-$users->count()); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor

                </tbody>
                </table>
                <div class="mt-3">
                    {{ $users->links('admin.custom-pagination') }}
                </div>

            </div>
        </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.addEventListener('close-modal', event => {
            $('#ChooseModal').modal('hide');
        });
    </script>
    @endpush
</div>
