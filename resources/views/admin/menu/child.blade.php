@foreach ($menus as $menu)
    <li class="menu-item {{ request()->is($menu->action) ? 'active' : '' }}">
        <a href="{!! count($menu->childs) > 0 ? 'javascript:void(0)' : url($menu->action) !!}" class="menu-link {{ count($menu->childs) > 0 ? 'menu-toggle' : '' }}">
            <div>{{ $menu->name }}</div>
        </a>
        @if (count($menu->childs))
            <ul class="menu-sub">
                @include('admin.menu.child', ['childs' => $menu->childs])
            </ul>
        @endif
    </li>
@endforeach
