<div>
    @section('title', 'Menu Manager')
    @push('head') <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script> @endpush

    <div class="row">
        <div class="col-md-6">
            <h2 class="fs-3"><span class="text-muted fw-light">Menu /</span> Manager</h2>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb d-flex justify-content-end">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/admin') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">System</a>
                    </li>
                    <li class="breadcrumb-item active">Menu Manager</li>
                </ol>
            </nav>
        </div>
    </div>

    <x-flash-alert />

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">

                    <form wire:submit.prevent="save_order">
                    <ul class="list-group nested-sortable mb-4">
                        @foreach($menuLists as $menu)
                        <li class="list-group-item {{ ($set_id==$menu->id) ? 'active' : '' }}" data-id="{{ $menu->id }}">
                            <div class="d-md-flex justify-content-between">
                                {{ $menu->title }}
                                <span wire:click="edit({{ $menu->id }})" class="cursor-pointer text-primary" style="font-size:10px;">Edit</span>
                            </div>
                            <ul class="list-group nested-sortable" data-id="{{ $menu->id }}">
                            @if(count($menu->childs)>0)
                                @include('livewire.menu-manager.menu-child',['menus' => $menu->childs])
                            @endif
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary" onclick="save_order()">Save Order</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <form wire:submit.prevent="store">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" wire:model="title" class="form-control @error('title') is-invalid @enderror" placeholder="Title">
                        @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent</label>
                        <select wire:model="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                            <option value="0">Top Menu</option>
                            @foreach( $menuLists as $menu )
                            <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                            @if(count($menu->childs)>0)
                            @include('livewire.menu-manager.menu-option',['menus' => $menu->childs])
                            @endif
                            @endforeach
                        </select>
                        @error('parent_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Url</label>
                        <input type="text" wire:model="url" class="form-control @error('url') is-invalid @enderror" placeholder="Url">
                        @error('url')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Scope</label>
                        <input type="text" wire:model="scope" class="form-control @error('scope') is-invalid @enderror" placeholder="Scope">
                        @error('scope')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Icon</label>
                        <input type="text" wire:model="icon" class="form-control @error('icon') is-invalid @enderror" placeholder="Icon">
                        @error('icon')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary me-2 mb-3">Save changes</button>
                    @if(!empty($set_id))
                    <form wire:submit.prevent="formReset">
                        <button type="submit" class="btn btn-warning me-2 mb-3">Cancel</button>
                    </form>
                    <form wire:submit.prevent="delete">
                        <button type="submit" class="btn btn-danger mb-3">Delete</button>
                    </form>
                    @endif
                </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    var nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));
    // Loop through each nested sortable element
    for (var i = 0; i < nestedSortables.length; i++) {
        new Sortable(nestedSortables[i], {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onEnd : function(e){
                console.log(e);
                parent_id = $(e.to).attr('data-id');
                id = $(e.item).attr('data-id');
                if(parent_id==undefined) parent_id = '0';
                $.ajax({
                    url: '{{ url('menu-save-parent') }}',
                    data: {
                        'id' : id,
                        'parent_id' : parent_id,
                        '_token' : '{{ csrf_token() }}',
                    },
                    method: 'POST',
                    dataType: 'json',
                    cache: false,
                    success: function(res){
                        if(res.success=='1'){
                            //document.location.href='{{ url('/admin/menu-manager') }}';
                        }else{
                        }
                    },
                    complete: function(){},
                    error: function(){},
                });
            }
        });
    }

    function save_order()
    {
        tmp = [];
        $('.list-group-item').each(function(key,val){
            id = $(val).attr('data-id');
            tmp.push(id);
        });
        $.ajax({
            url: '{{ url('menu-save-order') }}',
            data: {
                'order' : JSON.stringify(tmp),
                '_token' : '{{ csrf_token() }}',
            },
            method: 'POST',
            dataType: 'json',
            cache: false,
            success: function(res){
                if(res.success=='1'){
                    document.location.href='{{ url('/admin/menu-manager') }}';
                }
            },
            complete: function(){},
            error: function(){},
        });
    }
    </script>
    @endpush
</div>
