<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Sarpras;
use App\Models\User;
use App\Models\ActionLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DummyAnalyticsSeeder extends Seeder
{
    public function run()
    {
        // 1. Dapatkan user petugas dan peminjam
        $petugas = User::where('role', 'petugas')->first() ?? User::first();
        $peminjam = User::where('role', 'pengguna')->first();
        
        if (!$peminjam) {
            $this->command->info('Tidak ada user peminjam. Skip seeding analytics.');
            return;
        }

        // 2. Buat beberapa peminjaman yang sudah selesai (dikembalikan)
        // Kita buat data mundur 6 bulan ke belakang
        
        $sarprasItems = Sarpras::all();
        if ($sarprasItems->count() == 0) {
            $this->command->info('Tidak ada sarpras. Skip seeding analytics.');
            return;
        }

        $conditions = ['rusak_ringan', 'rusak_berat', 'hilang', 'baik', 'baik']; // Weighted to 'baik' but incl damage

        for ($i = 0; $i < 20; $i++) {
            $item = $sarprasItems->random();
            $tglPinjam = Carbon::now()->subMonths(rand(0, 6))->subDays(rand(1, 28));
            $tglKembali = (clone $tglPinjam)->addDays(rand(1, 7));

            DB::transaction(function() use ($item, $tglPinjam, $tglKembali, $peminjam, $petugas, $conditions) {
                // Create Peminjaman
                $peminjaman = Peminjaman::create([
                    'sarpras_id' => $item->id,
                    'user_id' => $peminjam->id,
                    'tgl_pinjam' => $tglPinjam,
                    'tgl_kembali_rencana' => $tglKembali,
                    'status' => 'dikembalikan', // Pastikan status sudah selesai
                    'tujuan' => 'Keperluan dummy data analytics',
                    'jumlah' => 1,
                    'disetujui_oleh' => $petugas->id,
                    'kode_peminjaman' => 'DUMMY-' . uniqid(), 
                ]);

                // Create Pengembalian
                $kondisi = $conditions[array_rand($conditions)];
                
                Pengembalian::create([
                    'peminjaman_id' => $peminjaman->id,
                    'tgl_pengembalian' => $tglKembali,
                    'kondisi_alat' => $kondisi,
                    'catatan_petugas' => 'Auto generated dummy data for testing charts',
                    'diterima_oleh' => $petugas->id,
                ]);

                // Update kondisi barang jika rusak/hilang
                if ($kondisi != 'baik') {
                    // Map kondisi pengembalian ke kondisi sarpras
                    // Sarpras mungkin tidak punya status 'hilang', jadi kita pakai rusak_berat atau abaikan
                    $newKondisi = ($kondisi == 'hilang') ? 'rusak_berat' : $kondisi;
                    
                    // Pastikan valid enum (baik, rusak_ringan, rusak_berat)
                    // Jika kondisi adalah 'butuh_maintenance' dll, sesuaikan.
                    // Untuk aman, kita hanya update jika rusak_ringan atau rusak_berat
                    if (in_array($newKondisi, ['rusak_ringan', 'rusak_berat'])) {
                        $item->update(['kondisi' => $newKondisi]);
                    }
                }
            });
        }
        
        $this->command->info('Dummy analytics data created successfully.');
    }
}
