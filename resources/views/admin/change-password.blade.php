@extends('admin.layouts.main')

@section('title', 'Change Password')

@section('content')

    <x-flash-alert />

    <h1 class="mb-3"><span class="text-muted fw-light">Change Password</span></h1>

    <div class="row">
        <div class="col-md-4">
            @livewire('auth.change-password')
        </div>
    </div>

@endsection
