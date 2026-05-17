<?php

namespace Tests\Feature\Weather;

use Tests\TestCase;

class WeatherForecastTest extends TestCase
{
    public function test_weather_page_can_be_opened(): void
    {
        $response = $this->get('/weather');

        $response->assertStatus(200);
    }
}
?>
