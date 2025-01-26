<div>
    {{-- <x-flash-alert /> --}}
    @if (session()->has('message'))
        <h5 class="alert alert-success mb-3">{{ session('message') }}</h5>
    @endif
    <table class="table card-table table-hover table-striped table-sm">
    <thead>
    <tr class="border-top">
        <th class="w-px-75">No</th>
        <th class="sort" wire:click="sortOrder('name')">Name {!! $sortLink !!}</th>
        <th class="w-px-150">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($roles as $role)
    <tr>
        <td>{{ ($roles->currentPage()-1) * $roles->perPage() + $loop->index + 1 }}</td>
        <td class="border-start">{{ $role->name }}</td>
        <td class="border-start text-center">
            <button type="button" wire:click="edit('{{ $role->id }}')" class="btn btn-xs btn-info me-2" data-bs-toggle="modal" data-bs-target="#RoleEditModal">Update</button>
            <button type="button" wire:click="delete('{{ $role->id }}')" class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#RoleDeleteModal">Del</button>
        </td>
    </tr>
    @endforeach

    @for($i=1; $i<=($roles->perPage()-$roles->count()); $i++)
    <tr>
        <td>&nbsp;</td>
        <td class="border-start">&nbsp;</td>
        <td class="border-start">&nbsp;</td>
    </tr>
    @endfor
    </tbody>
    </table>
    <div class="mt-3">
        {{ $roles->links('admin.custom-pagination') }}
    </div>

    @include('livewire.role.role-modal')
</div>
