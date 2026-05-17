<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeatherController extends Controller
{
    public function index(Request $request, WeatherService $weatherService): View
    {
        // 1. Get city from input

    if ($request->has('city')) {
            $city = $request->input('city');
            $weatherData = $weatherService->getWeatherData($city);
            return view('weather.index', ['weatherData' => $weatherData]);
        }



        // 2. Ask WeatherService for weather data

if ($request->has('latitude') && $request->has('longitude')) {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $forecastData = $weatherService->getForecast($latitude, $longitude);
            return view('weather.index', ['forecastData' => $forecastData]);
        }



        // 3. Return weather view with data


    if ($request->has('query')) {
            $query = $request->input('query');
            $searchResults = $weatherService->searchCity($query);
            return view('weather.index', ['searchResults' => $searchResults]);
        }

        return view('weather.index');
    }
}
