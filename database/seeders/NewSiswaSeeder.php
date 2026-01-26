<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NewSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            ['nisn' => '0078320930', 'name' => 'ABDUL HAFIZH AZIZ'],
            ['nisn' => '0077584240', 'name' => 'ADITYA HERMANA PUTRA'],
            ['nisn' => '0075132987', 'name' => 'AHMAD YOGA SETIAWAN'],
            ['nisn' => '0073908882', 'name' => 'AKSEL DELVINO RADINKA PRATAMA'],
            ['nisn' => '0079696120', 'name' => 'APRILIA DWI LESTARI'],
            ['nisn' => '0083395255', 'name' => 'ARKA PANGESTU WIBOWO'],
            ['nisn' => '0081887843', 'name' => 'BINTI DZURIATUS SHOLIHAH'],
            ['nisn' => '0071734863', 'name' => 'CAHYA BUANA INDAH'],
            ['nisn' => '0076875404', 'name' => 'CAHYA LANGIT ATMAWINATA'],
            ['nisn' => '0079331565', 'name' => 'CHAFID NOUVAL PUTRA'],
            ['nisn' => '0074303390', 'name' => 'CHAISYA DWI SEPTA RAHMADHANI'],
            ['nisn' => '0075931489', 'name' => 'CHEYRIL ATHIYYA DEVRILIA'],
            ['nisn' => '0072159818', 'name' => 'DAFFA ABIYYU ASYQAR'],
            ['nisn' => '0078169533', 'name' => 'DAFFA FADILLILAH NUR ISKANDAR'],
            ['nisn' => '0071358932', 'name' => 'DIMAS SETIA PRATAMA'],
            ['nisn' => '0075840594', 'name' => 'DION FARADO'],
            ['nisn' => '0085852668', 'name' => 'DIVA ANANDA KARTIKA'],
            ['nisn' => '0076018561', 'name' => 'DIVA LIVIA PURBASARI'],
            ['nisn' => '0076451181', 'name' => 'FAHRIZ ALGHIFARI'],
            ['nisn' => '0081984121', 'name' => 'FEBRIANA ANDRA SARI'],
            ['nisn' => '0071628194', 'name' => 'ILHAM FRIDO BAGASKARA'],
            ['nisn' => '0071412781', 'name' => 'INDIRA FAZA RAHMADHANI'],
            ['nisn' => '0073470159', 'name' => 'INTANIA CAHYA KIRANI'],
            ['nisn' => '0071551815', 'name' => 'IRSYAD ARIF'],
            ['nisn' => '0076645687', 'name' => 'JOVIAN HELGA KUMARA'],
            ['nisn' => '0075497995', 'name' => 'KEVIN JULIANO ARVAREAN'],
            ['nisn' => '0079715820', 'name' => 'KHAIRUNNIZAM'],
            ['nisn' => '0072761653', 'name' => 'LUVITA ANGGRAINI'],
            ['nisn' => '0074801967', 'name' => 'MOCH DANY MAULANA'],
            ['nisn' => '0079611907', 'name' => 'MOH FAJAR SATRIO UTOMO'],
            ['nisn' => '0081347058', 'name' => 'MUHAMMAD ANDREAN ALFARIZKI'],
            ['nisn' => '0072242351', 'name' => 'MUHAMMAD FAHRI IRVANDI'],
            ['nisn' => '0073286738', 'name' => 'MUHAMMAD FIRZATULLAH AQILA RISFAYADI'],
            ['nisn' => '0083900604', 'name' => 'MUHAMMAD HABIB AL KINDY'],
            ['nisn' => '0079704456', 'name' => 'MUHAMMAD NUR RIZQI'],
            ['nisn' => '0072952904', 'name' => 'MUHAMMAD RANGGA NUR RIDWAN'],
        ];

        foreach ($students as $student) {
            User::updateOrCreate(
                ['nisn' => $student['nisn']], // Unique check by NISN
                [
                    'name' => $student['name'],
                    'email' => $student['nisn'] . '@student.com', // Dummy email from NISN
                    'password' => Hash::make($student['nisn']), // Password = NISN
                    'role' => 'pengguna',
                ]
            );
        }
    }
}
