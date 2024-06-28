<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Fitur;
use App\Models\Setup;
use App\Models\Divisi;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\Jabatan;
use App\Models\Permission;
use App\Models\JenisBahanBaku;
use App\Models\JenisTransaksi;
use Illuminate\Database\Seeder;
use App\Models\JenisProdukAkhir;
use App\Models\JenisProdukSamping;
use App\Models\JenisProdukReproses;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $setup = [
            'company_name' => ucReplaceUnderscoreToSpace('CV._kendali_sinergi_aktif'),
            'company_logo' => 'https://img.freepik.com/free-vector/colorful-bird-illustration-gradient_343694-1741.jpg?w=740&t=st=1718777239~exp=1718777839~hmac=0fb64615a1d74bf2aa6359c4fa472ff75d881de15c103bdecd09f495a98a7e6c',
        ];
        Setup::insert($setup);

        $divisi = [
            ["nama" => ucReplaceUnderscoreToSpace('direktorat')],
            ["nama" => ucReplaceUnderscoreToSpace('PPIC')],
            ["nama" => ucReplaceUnderscoreToSpace('produksi')],
            ["nama" => ucReplaceUnderscoreToSpace('gudang')],
            ["nama" => ucReplaceUnderscoreToSpace('QC')],
            ["nama" => ucReplaceUnderscoreToSpace('R&D')],
        ];
        Divisi::insert($divisi);

        $jabatan = [
            ["nama" => ucReplaceUnderscoreToSpace('direktur_utama'), 'divisi_id' => 1],
            ["nama" => ucReplaceUnderscoreToSpace('kepala_PPIC'), 'divisi_id' => 2],
            ["nama" => ucReplaceUnderscoreToSpace('kepala_produksi'), 'divisi_id' => 3],
            ["nama" => ucReplaceUnderscoreToSpace('kepala_gudang'), 'divisi_id' => 4],
            ["nama" => ucReplaceUnderscoreToSpace('kepala_QC'), 'divisi_id' => 5],
            ["nama" => ucReplaceUnderscoreToSpace('kepala_R&D'), 'divisi_id' => 6],
        ];
        Jabatan::insert($jabatan);

        $pengguna = [
            ["nama" => ucReplaceUnderscoreToSpace('ivan_bernardi'), 'jabatan_id' => 1, 'username' => 'ivan', 'password' => bcrypt('ivan12345'), 'is_active' => 1],
            ["nama" => ucReplaceUnderscoreToSpace('sutomo'), 'jabatan_id' => 2, 'username' => 'tomo', 'password' => bcrypt('tomo12345'), 'is_active' => 1],
            ["nama" => ucReplaceUnderscoreToSpace('onto_saroyo'), 'jabatan_id' => 3, 'username' => 'yoyo', 'password' => bcrypt('yoyo12345'), 'is_active' => 1],
            ["nama" => ucReplaceUnderscoreToSpace('budi_raharjo'), 'jabatan_id' => 4, 'username' => 'budi', 'password' => bcrypt('budi12345'), 'is_active' => 1],
            ["nama" => ucReplaceUnderscoreToSpace('citra_hapsari'), 'jabatan_id' => 5, 'username' => 'citra', 'password' => bcrypt('citra12345'), 'is_active' => 1],
            ["nama" => ucReplaceUnderscoreToSpace('ardina_lesti'), 'jabatan_id' => 6, 'username' => 'dina', 'password' => bcrypt('dina12345'), 'is_active' => 1],
        ];
        User::insert($pengguna);

        $fitur = [
            ['nama' => ucwords(str_replace('_', ' ', 'setup')), 'rute' => 'setup.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'process_setup')), 'rute' => 'setup.process'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_divisi')), 'rute' => 'divisi.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_divisi')), 'rute' => 'divisi.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_divisi')), 'rute' => 'divisi.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_divisi')), 'rute' => 'divisi.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_divisi')), 'rute' => 'divisi.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_divisi')), 'rute' => 'divisi.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_jabatan')), 'rute' => 'jabatan.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_jabatan')), 'rute' => 'jabatan.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_jabatan')), 'rute' => 'jabatan.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_jabatan')), 'rute' => 'jabatan.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_jabatan')), 'rute' => 'jabatan.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_jabatan')), 'rute' => 'jabatan.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_user')), 'rute' => 'user.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_user')), 'rute' => 'user.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_user')), 'rute' => 'user.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_user')), 'rute' => 'user.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_user')), 'rute' => 'user.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_user')), 'rute' => 'user.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'log_aktifitas')), 'rute' => 'log_aktifitas'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_satuan')), 'rute' => 'satuan.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_satuan')), 'rute' => 'satuan.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_satuan')), 'rute' => 'satuan.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_satuan')), 'rute' => 'satuan.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_satuan')), 'rute' => 'satuan.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_satuan')), 'rute' => 'satuan.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_gudang')), 'rute' => 'gudang.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_gudang')), 'rute' => 'gudang.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_gudang')), 'rute' => 'gudang.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_gudang')), 'rute' => 'gudang.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_gudang')), 'rute' => 'gudang.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_gudang')), 'rute' => 'gudang.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_jenis_transaksi')), 'rute' => 'jenis_transaksi.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_jenis_transaksi')), 'rute' => 'jenis_transaksi.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_jenis_transaksi')), 'rute' => 'jenis_transaksi.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_jenis_transaksi')), 'rute' => 'jenis_transaksi.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_jenis_transaksi')), 'rute' => 'jenis_transaksi.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_jenis_transaksi')), 'rute' => 'jenis_transaksi.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_jenis_bahan_baku')), 'rute' => 'jenis_bahan_baku.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_jenis_bahan_baku')), 'rute' => 'jenis_bahan_baku.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_jenis_bahan_baku')), 'rute' => 'jenis_bahan_baku.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_jenis_bahan_baku')), 'rute' => 'jenis_bahan_baku.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_jenis_bahan_baku')), 'rute' => 'jenis_bahan_baku.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_jenis_bahan_baku')), 'rute' => 'jenis_bahan_baku.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_jenis_produk_reproses')), 'rute' => 'jenis_produk_reproses.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_jenis_produk_reproses')), 'rute' => 'jenis_produk_reproses.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_jenis_produk_reproses')), 'rute' => 'jenis_produk_reproses.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_jenis_produk_reproses')), 'rute' => 'jenis_produk_reproses.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_jenis_produk_reproses')), 'rute' => 'jenis_produk_reproses.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_jenis_produk_reproses')), 'rute' => 'jenis_produk_reproses.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_jenis_produk_samping')), 'rute' => 'jenis_produk_samping.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_jenis_produk_samping')), 'rute' => 'jenis_produk_samping.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_jenis_produk_samping')), 'rute' => 'jenis_produk_samping.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_jenis_produk_samping')), 'rute' => 'jenis_produk_samping.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_jenis_produk_samping')), 'rute' => 'jenis_produk_samping.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_jenis_produk_samping')), 'rute' => 'jenis_produk_samping.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_jenis_produk_akhir')), 'rute' => 'jenis_produk_akhir.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_jenis_produk_akhir')), 'rute' => 'jenis_produk_akhir.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_jenis_produk_akhir')), 'rute' => 'jenis_produk_akhir.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_jenis_produk_akhir')), 'rute' => 'jenis_produk_akhir.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_jenis_produk_akhir')), 'rute' => 'jenis_produk_akhir.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_jenis_produk_akhir')), 'rute' => 'jenis_produk_akhir.destroy'],
            ['nama' => ucwords(str_replace('_', ' ', 'daftar_bahan_baku')), 'rute' => 'bahan_baku.index'],
            ['nama' => ucwords(str_replace('_', ' ', 'tambah_bahan_baku')), 'rute' => 'bahan_baku.create'],
            ['nama' => ucwords(str_replace('_', ' ', 'simpan_bahan_baku')), 'rute' => 'bahan_baku.store'],
            ['nama' => ucwords(str_replace('_', ' ', 'edit_bahan_baku')), 'rute' => 'bahan_baku.edit'],
            ['nama' => ucwords(str_replace('_', ' ', 'update_bahan_baku')), 'rute' => 'bahan_baku.update'],
            ['nama' => ucwords(str_replace('_', ' ', 'hapus_bahan_baku')), 'rute' => 'bahan_baku.destroy'],
        ];
        Fitur::insert($fitur);

        foreach (Fitur::select('id')->orderBy('id')->get() as $fitur_id) {
            Permission::insert([
                "fitur_id" => $fitur_id->id,
                "jabatan_id" => 1,
            ]);
        }

        $satuan = [
            ["nama" => ucReplaceUnderscoreToSpace('pack')],
            ["nama" => ucReplaceUnderscoreToSpace('box')],
            ["nama" => ucReplaceUnderscoreToSpace('kuintal')],
            ["nama" => ucReplaceUnderscoreToSpace('kilogram')],
            ["nama" => ucReplaceUnderscoreToSpace('ons')],
            ["nama" => ucReplaceUnderscoreToSpace('gram')],
        ];
        Satuan::insert($satuan);

        $gudang = [
            ["nama" => ucReplaceUnderscoreToSpace('gudang_a')],
            ["nama" => ucReplaceUnderscoreToSpace('gudang_b')],
            ["nama" => ucReplaceUnderscoreToSpace('gudang_c')],
        ];
        Gudang::insert($gudang);

        $jenis_transaksi = [
            ["nama" => ucReplaceUnderscoreToSpace('serah_terima_hasil_produksi'), 'saldo' => 'plus'],
            ["nama" => ucReplaceUnderscoreToSpace('kebutuhan_bahan_produksi'), 'saldo' => 'minus'],
        ];
        JenisTransaksi::insert($jenis_transaksi);

        $jenis_bahan_baku = [
            ["nama" => ucReplaceUnderscoreToSpace('tepung')],
            ["nama" => ucReplaceUnderscoreToSpace('seasoning')],
            ["nama" => ucReplaceUnderscoreToSpace('plastik_kemasan')],
            ["nama" => ucReplaceUnderscoreToSpace('tray')],
        ];
        JenisBahanBaku::insert($jenis_bahan_baku);

        $jenis_produk_reproses = [
            ["nama" => ucReplaceUnderscoreToSpace('filling')],
            ["nama" => ucReplaceUnderscoreToSpace('sambal')],
        ];
        JenisProdukReproses::insert($jenis_produk_reproses);

        $jenis_produk_samping = [
            ["nama" => ucReplaceUnderscoreToSpace('tetes')],
            ["nama" => ucReplaceUnderscoreToSpace('ampas')],
        ];
        JenisProdukSamping::insert($jenis_produk_samping);

        $jenis_produk_akhir = [
            ["nama" => ucReplaceUnderscoreToSpace('meat')],
            ["nama" => ucReplaceUnderscoreToSpace('bakery')],
            ["nama" => ucReplaceUnderscoreToSpace('saus')],
        ];
        JenisProdukAkhir::insert($jenis_produk_akhir);

    }
}