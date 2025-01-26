
@extends('admin.layouts.main')

@section('title', 'Profil Sekolah')

@section('content')

  <x-flash-alert />

  <h1 class="mb-3"><span class="text-muted fw-light">@yield('title')</span></h1>

  <div class="row">
    <div class="col-md-4">
      @livewire('auth.change-images')
    </div>
    <div class="col-md-4">
      @livewire('auth.change-profile')
    </div>
  </div>

@endsection
