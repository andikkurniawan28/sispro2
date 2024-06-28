@extends('template.kaiadmin.master')

@section('title')
    {{ ucReplaceUnderscoreToSpace('setup') }}
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
            <a href="{{ route('setup.index') }}">{{ ucReplaceUnderscoreToSpace('setup') }}</a>
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
                            <div class="col-md-6 col-lg-4">
                                <form action="{{ route('setup.process') }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('POST')
                                <div class="form-group">
                                    <label for="company_name">{{ ucReplaceUnderscoreToSpace('company_name') }}</label>
                                    <input type="text" class="form-control" name="company_name" value="{{ $setup->company_name }}" required/>
                                </div>
                                <div class="form-group">
                                    <label for="company_logo">{{ ucReplaceUnderscoreToSpace('company_logo') }}</label>
                                    @if($setup->company_logo)
                                        <div class="mb-3">
                                            <img src="{{ asset($setup->company_logo) }}" alt="{{ ucReplaceUnderscoreToSpace('company_logo') }}"
                                                style="max-height: 100px; max-width: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="company_logo" name="company_logo" accept=".jpg, .jpeg, .png">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
