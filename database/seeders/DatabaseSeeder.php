<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Fitur;
use App\Models\Setup;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Permission;
use Illuminate\Database\Seeder;

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
        ];
        Fitur::insert($fitur);

        foreach (Fitur::select('id')->orderBy('id')->get() as $fitur_id) {
            Permission::insert([
                "fitur_id" => $fitur_id->id,
                "jabatan_id" => 1,
            ]);
        }

    }
}
