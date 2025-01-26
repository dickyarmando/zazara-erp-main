<div>
    <form wire:submit.prevent="store">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Informasi Sekolah</h5>
        </div>
        <div class="card-body">
            <x-flash-alert />
            <div class="mb-3">
                <label for="name" class="form-label">Nama Sekolah</label>
                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" wire:model="name" />
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat Sekolah</label>
                <textarea class="form-control" name="address" id="address" wire:model="address"></textarea>
                @error('address')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Simpan Data</button>
            </div>
        </div>
    </div>
    </form>
</div>
