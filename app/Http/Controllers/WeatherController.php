<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\City;
use Carbon\Carbon;
use App\Models\Weather;

class WeatherController extends Controller
{
    public function checkWeather(Request $request)
    {
        $id = $request->input('cityId');
        $city = City::find($id);
        $cachedWeather = Weather::where('city_id', $city->id)->whereDate('created_at', Carbon::today())->first();

        if (!$cachedWeather) {
            $response = Http::get('https://api.open-meteo.com/v1/forecast?latitude=' . $city->lat . '&longitude=' . $city->lon . '&hourly=temperature_2m,rain');
            $weather = $response->json();

            for ($i = 0; $i < count($weather['hourly']['time']); $i++) {
                Weather::create([
                    'city_id' => $city->id,
                    'time' => $weather['hourly']['time'][$i],
                    'temperature' => $weather['hourly']['temperature_2m'][$i],
                    'rain' => $weather['hourly']['rain'][$i],
                ]);
            }
        }

        return response()->json(Weather::where('city_id', $city->id)->whereDate('created_at', Carbon::today())->select('time', 'rain', 'temperature')->get());
    }
}
