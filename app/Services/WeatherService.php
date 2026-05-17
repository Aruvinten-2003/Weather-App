<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function searchCity(string $query): array
    {
        $response = $this->weatherHttp()->get('https://geocoding-api.open-meteo.com/v1/search', [
            'name' => $query,
            'count' => 5,
            'language' => 'en',
            'format' => 'json',
        ]);

        if (! $response->successful()) {
            return [];
        }

        return $response->json('results', []);
    }

    public function getForecast(float $latitude, float $longitude): array
    {
        $response = $this->weatherHttp()->get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,wind_speed_10m,weather_code',
            'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_probability_max,weather_code',
            'timezone' => 'auto',
        ]);

        if (! $response->successful()) {
            return [];
        }

        $data = $response->json();
        $daily = $data['daily'] ?? [];
        $forecast = [];

        foreach (($daily['time'] ?? []) as $index => $date) {
            $forecast[] = [
                'date' => $date,
                'max_temperature' => $daily['temperature_2m_max'][$index] ?? null,
                'min_temperature' => $daily['temperature_2m_min'][$index] ?? null,
                'rain_chance' => $daily['precipitation_probability_max'][$index] ?? null,
                'condition' => $this->weatherCodeToText($daily['weather_code'][$index] ?? null),
            ];
        }

        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => [
                'temperature' => $data['current']['temperature_2m'] ?? null,
                'wind_speed' => $data['current']['wind_speed_10m'] ?? null,
                'condition' => $this->weatherCodeToText($data['current']['weather_code'] ?? null),
            ],
            'forecast' => $forecast,
        ];
    }

    public function getWeatherData(string $city): array
    {
        $cities = $this->searchCity($city);
        $place = $cities[0] ?? null;

        if (! $place) {
            return [
                'error' => 'City not found. Please try another city name.',
            ];
        }

        $forecast = $this->getForecast((float) $place['latitude'], (float) $place['longitude']);

        if ($forecast === []) {
            return [
                'error' => 'Weather data is not available right now. Please try again later.',
            ];
        }

        return [
            'city' => $place['name'],
            'country' => $place['country'] ?? '',
            'temperature' => $forecast['current']['temperature'],
            'wind_speed' => $forecast['current']['wind_speed'],
            'condition' => $forecast['current']['condition'],
            'forecast' => $forecast['forecast'],
        ];
    }

    private function weatherCodeToText(?int $code): string
    {
        return match ($code) {
            0 => 'Clear sky',
            1, 2, 3 => 'Partly cloudy',
            45, 48 => 'Fog',
            51, 53, 55 => 'Drizzle',
            61, 63, 65 => 'Rain',
            71, 73, 75 => 'Snow',
            80, 81, 82 => 'Rain showers',
            95 => 'Thunderstorm',
            96, 99 => 'Thunderstorm with hail',
            default => 'Unknown',
        };
    }

    private function weatherHttp(): PendingRequest
    {
        $client = Http::timeout(10);
        $caBundle = storage_path('app/cacert.pem');

        if (is_file($caBundle)) {
            $client = $client->withOptions([
                'verify' => $caBundle,
            ]);
        }

        return $client;
    }
}
