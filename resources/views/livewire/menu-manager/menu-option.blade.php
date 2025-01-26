@foreach($menus as $menu)
    <option value="{{ $menu->id }}">|__{{ $menu->title }}</option>
    @if(count($menu->childs))
        @include('livewire.menu-manager.menu-option',['menus' => $menu->childs])
    @endif

@endforeach
