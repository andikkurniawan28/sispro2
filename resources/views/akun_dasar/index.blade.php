@extends('template.kaiadmin.master')

@section('title')
    {{ ucReplaceUnderscoreToSpace('akun_dasar') }}
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
            <a href="{{ route('akun_dasar.index') }}">@yield('title')</a>
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
                            <div class="card-title text-white text-right">
                                <a href="{{ route('akun_dasar.create') }}" class="btn btn-sm btn-primary text-white">Tambah</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mt-4" id="akun_dasar-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ ucReplaceUnderscoreToSpace('kode') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('nama') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('laporan') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('kelompok') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('tindakan') }}</th>
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
            $('#akun_dasar-table').DataTable({
                order: [
                    [0, 'desc']
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('akun_dasar.index') }}",
                columns: [
                    { data: 'kode', name: 'kode' },
                    { data: 'nama', name: 'nama' },
                    { data: 'laporan', name: 'laporan' },
                    { data: 'kelompok', name: 'kelompok' },
                    { data: 'tindakan', name: 'tindakan', orderable: false, searchable: false },
                ]
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('delete-btn')) {
                    event.preventDefault();
                    const button = event.target;
                    const akun_dasar_id = button.getAttribute('data-id');
                    const akun_dasar_nama = button.getAttribute('data-nama');
                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfTokenElement) {
                        console.error('CSRF token not found!');
                        return;
                    }
                    const csrfToken = csrfTokenElement.content;

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Anda tidak bisa mengembalikan data yang terhapus!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, saya yakin!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.setAttribute('method', 'POST');
                            form.setAttribute('action', `{{ url('akun_dasar') }}/${akun_dasar_id}`);
                            form.setAttribute('style', 'display: none;'); // Optional: Hide the form

                            const hiddenMethod = document.createElement('input');
                            hiddenMethod.setAttribute('type', 'hidden');
                            hiddenMethod.setAttribute('name', '_method');
                            hiddenMethod.setAttribute('value', 'DELETE');

                            const csrfTokenInput = document.createElement('input');
                            csrfTokenInput.setAttribute('type', 'hidden');
                            csrfTokenInput.setAttribute('name', '_token');
                            csrfTokenInput.setAttribute('value', csrfToken);

                            form.appendChild(hiddenMethod);
                            form.appendChild(csrfTokenInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
