<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard') }}" class="logo">
                <img src="{{ $setup->company_logo }}" alt="navbar brand"
                    class="navbar-brand" height="20" />
                <span class="navbar-brand menu-text fw-bolder ms-2 text-white">{{ ucReplaceUnderscoreToSpace('sisPRO') }}</span>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <li class="nav-item @yield('dashboard-aktif')">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>{{ ucReplaceUnderscoreToSpace('dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-item @yield('akses-aktif')">
                    <a data-bs-toggle="collapse" href="#akses">
                        <i class="fas fa-door-open"></i>
                        <p>{{ ucReplaceUnderscoreToSpace('akses') }}</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="akses">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('divisi.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('divisi') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jabatan.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('jabatan') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('user') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('log_aktifitas') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('log_aktifitas') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item @yield('master-aktif')">
                    <a data-bs-toggle="collapse" href="#master">
                        <i class="fas fa-database"></i>
                        <p>{{ ucReplaceUnderscoreToSpace('master') }}</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="master">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('satuan.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('satuan') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jenis_transaksi.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('jenis_transaksi') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jenis_bahan_baku.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('jenis_bahan_baku') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jenis_produk_reproses.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('jenis_produk_reproses') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jenis_produk_samping.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('jenis_produk_samping') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jenis_produk_akhir.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('jenis_produk_akhir') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('parameter_proses_produksi') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('parameter_kualitas_produksi') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('parameter_kualitas_sarpras') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#persediaan">
                        <i class="fas fa-warehouse"></i>
                        <p>{{ ucReplaceUnderscoreToSpace('persediaan') }}</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="persediaan">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('gudang.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('gudang') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('bahan_baku.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('bahan_baku') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('permintaan.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('permintaan') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('transaksi') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#produksi">
                        <i class="fas fa-industry"></i>
                        <p>{{ ucReplaceUnderscoreToSpace('produksi') }}</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="produksi">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('produk_reproses.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('produk_reproses') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('produk_samping.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('produk_samping') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('produk_akhir.index') }}">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('produk_akhir') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('proses_produksi') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#kualitas">
                        <i class="fas fa-flask"></i>
                        <p>{{ ucReplaceUnderscoreToSpace('kualitas') }}</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="kualitas">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('kualitas_produksi') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">{{ ucReplaceUnderscoreToSpace('kualitas_sarpras') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
