<div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Change Password</h5>
        </div>
        <div class="card-body">
            <x-flash-alert />
            <form wire:submit.prevent="store">
                @csrf
                <div class="mb-3">
                  <label for="current_password" class="form-label">Current Password</label>
                  <input type="password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" wire:model="current_password" autofocus />
                  @error('current_password')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="new_password">New Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" wire:model="new_password"  />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  @error('new_password')
                    <div class="d-block invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="new_password_confirmation">Re-Type New Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" wire:model="new_password_confirmation" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  @error('new_password_confirmation')
                    <div class="d-block invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div>
                  <button class="btn btn-primary d-grid w-100" type="submit">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
