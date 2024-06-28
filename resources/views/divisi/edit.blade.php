@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('divisi') }}
@endsection

@section('navigation')
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('dashboard') }}">
                <i class="icon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('divisi.index') }}">{{ ucReplaceUnderscoreToSpace('divisi') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('divisi.edit', $divisi->id) }}">@yield('title')</a>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">@yield('title')</h4>
            @yield('navigation')
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('divisi.update', $divisi->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $divisi->nama }}" placeholder="Masukkan nama ..." required autofocus>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
