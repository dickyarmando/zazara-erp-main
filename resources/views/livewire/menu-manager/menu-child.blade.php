@foreach($menus as $menu)
<li class="list-group-item {{ ($set_id==$menu->id) ? 'active' : '' }}" data-id="{{ $menu->id }}">
    <div class="d-md-flex justify-content-between">
        {{ $menu->title }}
        <span wire:click="edit({{ $menu->id }})" class="cursor-pointer text-primary" style="font-size:10px;">Edit</span>
    </div>
    <ul class="list-group nested-sortable" data-id="{{ $menu->id }}">
    @if(count($menu->childs))
        @include('livewire.menu-manager.menu-child',['menus' => $menu->childs])
    @endif
    </ul>
</li>
@endforeach
