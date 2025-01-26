@inject('menus', 'App\Models\PrmMenus')

@php
    $roles = \Illuminate\Support\Facades\Auth::user()->role_id;
@endphp

@foreach ($menus
        ::join('prm_role_menus', 'prm_role_menus.menu_id', '=', 'prm_menus.id')->where('prm_menus.parent_id', '=', '0')->where('prm_role_menus.role_id', '=', \Illuminate\Support\Facades\Auth::user()->role_id)->select('prm_menus.*')->with([
            'childs' => function ($q) use ($roles) {
                $q->join('prm_role_menus', 'prm_role_menus.menu_id', '=', 'prm_menus.id')->where('prm_role_menus.role_id', $roles);
            },
        ])->orderBy('prm_menus.seq', 'asc')->get() as $menu)
    @if (count($menu->childs) > 0)
        <li class="menu-item {{ request()->routeIs($menu->scope) ? 'active open' : '' }}">
        @else
        <li class="menu-item {{ request()->is($menu->action) ? 'active' : '' }}">
    @endif
    <a href="{!! count($menu->childs) > 0 ? 'javascript:void(0)' : url($menu->action) !!}" class="menu-link {{ count($menu->childs) > 0 ? 'menu-toggle' : '' }}">
        <i class="menu-icon tf-icons bx {{ $menu->icon }}"></i>
        <div>{{ $menu->name }}</div>
    </a>
    @if (count($menu->childs) > 0)
        <ul class="menu-sub">
            @include('admin.menu.child', ['menus' => $menu->childs])
        </ul>
    @endif
    </li>
@endforeach
