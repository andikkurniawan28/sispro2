@extends('template.kaiadmin.master')

@section('title')
    Detail {{ ucReplaceUnderscoreToSpace('permintaan') }}
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
            <a href="{{ route('permintaan.index') }}">{{ ucReplaceUnderscoreToSpace('permintaan') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('permintaan.index') }}">@yield('title')</a>
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
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card" id="invoice-section">
                        <div class="card-body">
                            <div class="invoice-header">
                                <h4 class="invoice-title">Faktur {{ ucReplaceUnderscoreToSpace('permintaan') }}</h4>
                                <div class="invoice-info">
                                    <div>
                                        <strong>Kode:</strong> {{ $permintaan->kode }}
                                    </div>
                                    <div>
                                        <strong>Dibuat pada:</strong>
                                        {{ date('d-m-Y H:i:s', strtotime($permintaan->created_at)) }}
                                    </div>
                                    <div>
                                        <strong>Berlaku sampai:</strong>
                                        {{ date('d-m-Y H:i:s', strtotime($permintaan->berlaku_sampai)) }}
                                    </div>
                                    <div>
                                        <strong>Diajukan Oleh:</strong> {{ $permintaan->user->nama }}
                                        ({{ $permintaan->user->jabatan->nama }})
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="invoice-body">
                                <h5>Detail Produk Akhir:</h5>
                                <br>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permintaan_detail as $index => $material)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ ucReplaceUnderscoreToSpace($material->material->kode) }}</td>
                                                <td>{{ ucReplaceUnderscoreToSpace($material->material->nama) }}</td>
                                                <td>{{ $material->jumlah }}</td>
                                                <td>{{ $material->material->satuan_besar->nama }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="invoice-footer">
                                <div style="margin-top: 20px;">
                                    <p style="text-align: right;">Mengetahui,</p>
                                    <br><br><br>
                                    <p style="text-align: right;">(_____________________)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button onclick="exportToPDF()" class="btn btn-primary" id="exportBtn">Export ke PDF</button>
                    <div id="loading" style="display: none;">
                        <img src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/0.16.1/images/loader-large.gif" alt="Loading...">
                        <p>Exporting...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #invoice-section, #invoice-section * {
                visibility: visible;
            }

            #invoice-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            @page {
                size: A4 portrait; /* Changed to portrait */
                margin: 0;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function exportToPDF() {
            // Show loading animation
            document.getElementById('exportBtn').style.display = 'none';
            document.getElementById('loading').style.display = 'block';

            const element = document.getElementById('invoice-section');

            html2pdf()
                .from(element)
                .set({
                    margin: [0.5, 0.5, 0.5, 0.5], // top, left, bottom, right margins
                    filename: "Faktur {{ ucReplaceUnderscoreToSpace('permintaan') }}.pdf",
                    image: { type: 'png', quality: 1 },
                    html2canvas: { scale: 4, logging: true },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' } // Changed to portrait
                })
                .save()
                .then(() => {
                    // Hide loading animation after PDF is saved
                    document.getElementById('exportBtn').style.display = 'block';
                    document.getElementById('loading').style.display = 'none';
                });
        }
    </script>
@endsection
