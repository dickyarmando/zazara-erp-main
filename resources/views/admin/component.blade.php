
@extends('admin.layouts.main')

@section('title', 'Component')

@section('content')

  <x-flash-alert />

  <h1 class="mb-3"><span class="text-muted fw-light">Auth /</span> Components</h1>

  <div class="row">
    <div class="col-md-4">
      @livewire('auth.change-profile')
    </div>
    <div class="col-md-4">
      @livewire('auth.change-avatar')
    </div>
    <div class="col-md-4">
      @livewire('auth.change-password')
    </div>
  </div>

  <h1 class="mt-5 mb-3"><span class="text-muted fw-light">Other /</span> Example</h1>

  <div class="row">
    <div class="col-md-4 mb-4">
      @livewire('example.input')
    </div>
    <div class="col-md-8 mb-4">
        @livewire('example.tabs')
    </div>
    <div class="col-md-8 mb-4">
        @livewire('example.detail')
    </div>
  </div>

  <h1 class="mt-5 mb-3"><span class="text-muted fw-light">User /</span> Master</h1>
    @livewire('user.user-table')
  </div>

@endsection
