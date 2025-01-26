
{{-- Create --}}
<div wire:ignore.self class="modal fade" id="RoleCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel3">Create Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form wire:submit.prevent="store">
        <div class="modal-body">

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name">
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
      </div>
    </div>
</div>

{{-- Edit --}}
<div wire:ignore.self class="modal fade" id="RoleEditModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form wire:submit.prevent="update">
        <div class="modal-body">

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name">
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" wire:click.prevent="update">Save changes</button>
        </div>
        </form>
      </div>
    </div>
</div>

{{-- Delete --}}
<div wire:ignore.self class="modal fade" id="RoleDeleteModal" tabindex="-1" role="dialog" aria-labelledby="xxx" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirm</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure want to delete?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="destroy()" class="btn btn-danger close-modal" data-bs-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('close-modal', event => {
        $('#RoleCreateModal').modal('hide');
        $('#RoleEditModal').modal('hide');
        $('#RoleDeleteModal').modal('hide');
    });
</script>
@endpush
