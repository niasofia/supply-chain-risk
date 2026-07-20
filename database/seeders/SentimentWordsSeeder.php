<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SentimentWordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Masukkan kata-kata positif ke tabel positive_words
        $positiveWords = [
            ['word' => 'growth'],
            ['word' => 'increase'],
            ['word' => 'profit'],
            ['word' => 'stable'],
            ['word' => 'improve'],
            ['word' => 'safe'],
            ['word' => 'success'],
        ];
        DB::table('positive_words')->insertOrIgnore($positiveWords);

        // 2. Masukkan kata-kata negatif ke tabel negative_words
        $negativeWords = [
            ['word' => 'war'],
            ['word' => 'crisis'],
            ['word' => 'inflation'],
            ['word' => 'delay'],
            ['word' => 'disaster'],
            ['word' => 'decrease'],
            ['word' => 'danger'],
        ];
        DB::table('negative_words')->insertOrIgnore($negativeWords);
    }
}