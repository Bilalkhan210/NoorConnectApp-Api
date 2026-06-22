<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Juz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuranController extends Controller
{
    public function getJuz($number)
    {
        if ($number < 1 || $number > 30) {
            return response()->json(['error' => 'Invalid Juz number. Must be between 1 and 30.'], 400);
        }

        $existingJuz = Juz::where('juz_number', $number)->first();

        if ($existingJuz) {
            return response()->json([
                'source' => 'local_database',
                'data' => $existingJuz->data
            ]);
        }

        try {
            $response = Http::get("https://ummahapi.com/api/quran/juz/{$number}");

            if ($response->successful()) {
                $apiData = $response->json();

                $newJuz = Juz::create([
                    'juz_number' => $number,
                    'data' => $apiData
                ]);

                return response()->json([
                    'source' => 'fetched_and_saved_to_db',
                    'data' => $newJuz->data
                ]);
            }

            return response()->json(['error' => 'Failed to fetch data from Ummah API'], $response->status());

        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
}
