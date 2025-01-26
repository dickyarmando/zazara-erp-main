
<div wire:ignore.self class="modal fade" id="UserFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" user="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">User</h5>
          <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
        </div>
        <form wire:submit.prevent="save">
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

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" placeholder="user@host.com">
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Avatar</label>
                <input type="file" wire:model="avatar"  class="form-control @error('avatar') is-invalid @enderror" />
                @if (!empty($showAvatar))
                <img src="{{ asset('avatar/'.$showAvatar) }}" alt="" class="w-px-auto h-px-50 rounded-circle mt-3" />
                @endif
                @error('avatar')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select wire:model="role" class="form-select @error('role') is-invalid @enderror">
                    @foreach(['','admin','guru','siswa','ortu'] as $row)
                    <option value="{{ $row }}" @if($row==$role) selected @endif>{{ $row }}</option>
                    @endforeach
                </select>
                @error('role')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" {{--data-bs-dismiss="modal"--}} wire:click="closeModal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
      </div>
    </div>
</div>

{{-- Delete --}}
<div wire:ignore.self class="modal fade" id="UserDeleteModal" tabindex="-1" user="dialog" aria-labelledby="xxx" aria-hidden="true">
    <div class="modal-dialog" user="document">
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
        $('#UserFormModal').modal('hide');
        $('#UserDeleteModal').modal('hide');
    });
</script>
@endpush
