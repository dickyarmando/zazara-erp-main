<div>
    <x-flash-alert />
    <div class="card">
        <div class="card-header d-md-flex align-items-center justify-content-between">
            <input type="text" class="form-control shadow-sm" placeholder="Search" style="width: 250px;" wire:model="searchKeyword" >
            <div class="btn-group shadow-sm">
                <button type="button" wire:click="roleFilter('')" class="btn btn-outline-secondary {{ ($roleFilter=='') ? 'active' : '' }}">All</button>
                @foreach(['admin','guru','siswa','ortu'] as $row)
                <button type="button" wire:click="roleFilter('{{ $row }}')" class="btn btn-outline-secondary {{ ($roleFilter==$row) ? 'active' : '' }}">{{ ucfirst($row) }}</button>
                @endforeach
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#UserFormModal"><i class="fa fa-plus me-2"></i>Create New</button>
        </div>
        <div class="table-responsive text-nowrap" class="position-relative">
            <div wire:loading class="position-absolute fs-1 top-50 start-50 z-3 text-info">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
            <table class="table card-table table-hover table-striped table-sm">
            <thead>
            <tr class="border-top">
                <th class="w-px-75">No</th>
                <th class="w-px-75">Avatar</th>
                <th style="width:30%;" class="sort" wire:click="sortOrder('name')">Name {!! $sortLink !!}</th>
                <th style="width:30%;" class="sort" wire:click="sortOrder('email')">Email {!! $sortLink !!}</th>
                <th class="sort w-px-150" wire:click="sortOrder('role')">Role {!! $sortLink !!}</th>
                <th class="w-px-150">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ ($users->currentPage()-1) * $users->perPage() + $loop->index + 1 }}</td>
                <td class="border-start py-1 text-center"><img src="{{ asset('avatar/'.$user->avatar) }}" alt="" class="w-px-auto h-px-30 rounded-circle" /></td>
                <td class="border-start">{{ $user->name }}</td>
                <td class="border-start">{{ $user->email }}</td>
                <td class="border-start">{{ $user->role }}</td>
                <td class="border-start text-center">
                    <button type="button" wire:click="edit('{{ $user->id }}')" class="btn btn-xs btn-info me-2" data-bs-toggle="modal" data-bs-target="#UserFormModal">Update</button>
                    <button type="button" wire:click="delete('{{ $user->id }}')" class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#UserDeleteModal">Del</button>
                </td>
            </tr>
            @endforeach

            @for($i=1; $i<=($users->perPage()-$users->count()); $i++)
            <tr>
                <td>&nbsp;</td>
                <td class="border-start">&nbsp;</td>
                <td class="border-start">&nbsp;</td>
                <td class="border-start">&nbsp;</td>
                <td class="border-start">&nbsp;</td>
                <td class="border-start">&nbsp;</td>
            </tr>
            @endfor
            </tbody>
            </table>
            <div class="mt-3">
                {{ $users->links('admin.custom-pagination') }}
            </div>
        </div>
    </div>
    @include('livewire.user.user-modal')
</div>
