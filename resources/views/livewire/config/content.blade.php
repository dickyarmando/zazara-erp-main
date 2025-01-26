@foreach ($configs as $k => $v)
    @if ($v['type'] == 'text')
        @include('livewire.config.textarea')
    @elseif($v['type'] == 'file')
        @include('livewire.config.file')
    @elseif($v['type'] == 'select_coa')
        @include('livewire.config.select-coa')
    @else
        @include('livewire.config.text')
    @endif
@endforeach
