<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    /**
     * Seed siswa/pengguna ke database.
     */
    public function run(): void
    {
        $siswa = [
            ['nisn' => '0078320930', 'name' => 'Abdul Hafizh Aziz'],
            ['nisn' => '0077584240', 'name' => 'Aditya Hermana Putra'],
            ['nisn' => '0075132987', 'name' => 'Ahmad Yoga Setiawan'],
            ['nisn' => '0073908882', 'name' => 'Aksel Delvino Radinka Pratama'],
            ['nisn' => '0079696120', 'name' => 'Aprilia Dwi Lestari'],
            ['nisn' => '0083395255', 'name' => 'Arka Pangestu Wibowo'],
            ['nisn' => '0081887843', 'name' => 'Binti Dzuriatus Sholihah'],
            ['nisn' => '0071734863', 'name' => 'Cahya Buana Indah'],
            ['nisn' => '0076875404', 'name' => 'Cahya Langit Atmawinata'],
            ['nisn' => '0079331565', 'name' => 'Chafid Nouval Putra'],
            ['nisn' => '0074303390', 'name' => 'Chaisya Dwi Septa Rahmadhani'],
            ['nisn' => '0075931489', 'name' => 'Cheyril Athiyya Devrilia'],
            ['nisn' => '0072159818', 'name' => 'Daffa Abiyyu Asyqar'],
            ['nisn' => '0078169533', 'name' => 'Daffa Fadlillah Nur Iskandar'],
            ['nisn' => '0071358932', 'name' => 'Dimas Setia Pratama'],
            ['nisn' => '0075840594', 'name' => 'Dion Farado'],
            ['nisn' => '0085852668', 'name' => 'Diva Ananda Kartika'],
            ['nisn' => '0076018561', 'name' => 'Diva Livia Purbasari'],
            ['nisn' => '0076451181', 'name' => 'Fahriz Alghifari'],
            ['nisn' => '0081984121', 'name' => 'Febriana Andra Sari'],
            ['nisn' => '0071628194', 'name' => 'Ilham Frido Bagaskara'],
            ['nisn' => '0071412781', 'name' => 'Indira Faza Rahmadhani'],
            ['nisn' => '0073470159', 'name' => 'Intania Cahya Kirani'],
            ['nisn' => '0071551815', 'name' => 'Irsyad Arif'],
            ['nisn' => '0076645687', 'name' => 'Jovian Helga Kumara'],
            ['nisn' => '0075497995', 'name' => 'Kevin Juliano Arvarean'],
            ['nisn' => '0079715820', 'name' => 'Khairunnizam'],
            ['nisn' => '0072761653', 'name' => 'Luvita Anggraini'],
            ['nisn' => '0074801967', 'name' => 'Moch Dany Maulana'],
            ['nisn' => '0079611907', 'name' => 'Moh Fajar Satrio Utomo'],
            ['nisn' => '0081347058', 'name' => 'Muhammad Andrean Alfarizki'],
            ['nisn' => '0072242351', 'name' => 'Muhammad Fahri Irvandi'],
            ['nisn' => '0073286738', 'name' => 'Muhammad Firzatullah Aqila Risfayadi'],
            ['nisn' => '0083900604', 'name' => 'Muhammad Habib Al Kindy'],
            ['nisn' => '0079704456', 'name' => 'Muhammad Nur Rizqi'],
            ['nisn' => '0072952904', 'name' => 'Muhammad Rangga Nur Ridwan'],
        ];

        foreach ($siswa as $s) {
            User::create([
                'name' => $s['name'],
                'nisn' => $s['nisn'],
                'email' => strtolower(str_replace(' ', '.', $s['name'])) . '@siswa.smk.sch.id',
                'password' => Hash::make($s['nisn']),
                'role' => 'pengguna',
            ]);
        }

        $this->command->info('36 siswa berhasil ditambahkan!');
    }
}
