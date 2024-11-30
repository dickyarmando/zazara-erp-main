<div>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <button type="button" wire:click="select(1)" class="nav-link {{ $tab_active == '1' ? 'active' : '' }}" tabindex="-1">Home</button>
        </li>
        <li class="nav-item">
            <button type="button" wire:click="select(2)" class="nav-link {{ $tab_active == '2' ? 'active' : '' }}" tabindex="-1">Profile</button>
        </li>
        <li class="nav-item">
            <button type="button" wire:click="select(3)" class="nav-link {{ $tab_active == '3' ? 'active' : '' }}" tabindex="-1">Messages</button>
        </li>
    </ul>

    @if($tab_active == '1')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Home</h5>
        </div>
        <div class="card-body">
            <p>Lorem ipsum dolor sit amet</p>
        </div>
    </div>
    @endif

    @if($tab_active == '2')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Profile</h5>
        </div>
        <div class="card-body">
            <p>Second tabs</p>
        </div>
    </div>
    @endif

    @if($tab_active == '3')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Messages</h5>
        </div>
        <div class="card-body">
            <p>Third tabs</p>
        </div>
    </div>
    @endif
</div>
