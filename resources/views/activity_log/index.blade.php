@extends('template.kaiadmin.master')

@section('title')
    {{ ucReplaceUnderscoreToSpace('log_aktifitas') }}
@endsection

@section('akses-aktif')
    {{ 'active' }}
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
            <a href="{{ route('log_aktifitas') }}">{{ ucReplaceUnderscoreToSpace('log_aktifitas') }}</a>
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
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mt-4" id="log_aktifitas-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ ucReplaceUnderscoreToSpace('timestamp') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('user') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('keterangan') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $('#log_aktifitas-table').DataTable({
                order: [
                    [0, 'desc']
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('log_aktifitas') }}",
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'user_nama', name: 'user.nama' },
                    { data: 'description', name: 'description' },
                ]
            });
        });
    </script>
@endsection
