
@extends('admin.layouts.main')

@section('title', 'Beranda')

@section('content')

  <x-flash-alert />

  <h1>@yield('title')</h1>

  <div class="row">
    <div class="col-md-6">
        @livewire('auth.mini-profile')
    </div>
    {{-- <div class="col-md-4">
      @livewire('auth.change-profile')
    </div>
    <div class="col-md-4">
      @livewire('auth.change-avatar')
    </div>
    <div class="col-md-4">
      @livewire('auth.change-password')
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Todo</h5>
            @livewire('todo.todo-create')
        </div>
        <div class="card-body">
          @livewire('todo.todo-table')
        </div>
      </div>
    </div> --}}
  </div>

@endsection
