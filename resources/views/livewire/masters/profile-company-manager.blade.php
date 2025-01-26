<div>
    @section('title', 'Profile Company')

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

    <div class="card">
        <form wire:submit.prevent="store">
            <div class="card-header d-md-flex align-items-center justify-content-between">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name Company <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Name Company">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email Company</label>
                                <input type="email" wire:model="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email Company">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Mobile Phone</label>
                                <input type="text" wire:model="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Mobile Phone">
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Telephone</label>
                                <input type="text" wire:model="telephone"
                                    class="form-control @error('telephone') is-invalid @enderror"
                                    placeholder="Telephone">
                                @error('telephone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Fax</label>
                                <input type="text" wire:model="fax"
                                    class="form-control @error('fax') is-invalid @enderror" placeholder="Fax">
                                @error('fax')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Website</label>
                                <input type="text" wire:model="website"
                                    class="form-control @error('website') is-invalid @enderror" placeholder="Website">
                                @error('website')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            @livewire('auth.change-images')
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea wire:model="address" class="form-control @error('address') is-invalid @enderror" rows="5"
                                    placeholder="Address"></textarea>
                                @error('website')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><span class="bx bx-save me-2"></span> Save Data</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script></script>
    @endpush
</div>
