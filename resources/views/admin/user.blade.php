
@extends('admin.layouts.main')

@section('title', 'Home')

@section('content')

<div class="d-flex align-items-center justify-content-between">
    <h2 class="fs-3"><span class="text-muted fw-light">User /</span> Manager</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('/admin') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="javascript:void(0);">System</a>
            </li>
            <li class="breadcrumb-item active">User Manager</li>
        </ol>
    </nav>
</div>

@livewire('user.user-table')

@endsection
