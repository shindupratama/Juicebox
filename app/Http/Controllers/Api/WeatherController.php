<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getCurrentWeather(string $q)
    {
        $apiKey = env('WEATHER_API_KEY');

        try {
            $response = Http::get('https://api.weatherapi.com/v1/current.json', [
                'key' => $apiKey,
                'q' => $q,
            ]);

            // return response()->json([
            //     'success' => true,
            //     'weather' => $response->json()
            // ], 201);

            return $response->json();
        } catch (\Exception $e) {
            // Handle exceptions (e.g., API errors, network issues)
            return response([
                'success' => false,
                'message' => $e,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
