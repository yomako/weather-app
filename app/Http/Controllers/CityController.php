<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\City;

class CityController extends Controller
{
    public function searchCity(Request $request)
    {
        $cityName = strtolower($request->input('cityName'));
        $cachedCity = City::where('name', $cityName)->first();

        if (!$cachedCity) {
            $response = Http::get('https://geocode.maps.co/search?q=' . str_replace(' ', '+', $cityName));
            $cities = $response->json();

            foreach ($cities as $city) {
                City::create([
                    'name' => $cityName,
                    'lat' => $city['lat'],
                    'lon' => $city['lon'],
                    'display_name' => $city['display_name']
                ]);
            }
        }

        return response()->json(City::where('name', $cityName)->get());
    }
}
