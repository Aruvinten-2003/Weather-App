<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast</title>

    <style>
        :root {
            --bg: #f5f8fb;
            --surface: #ffffff;
            --surface-soft: #eef6f5;
            --ink: #18222f;
            --muted: #667085;
            --line: #dbe5ea;
            --teal: #0f766e;
            --teal-dark: #115e59;
            --amber: #f59e0b;
            --rose: #e11d48;
            --shadow: 0 18px 45px rgba(24, 34, 47, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--ink);
            background:
                linear-gradient(135deg, rgba(15, 118, 110, 0.10), rgba(245, 158, 11, 0.12)),
                var(--bg);
        }

        .page {
            width: min(1080px, calc(100% - 32px));
            margin: 0 auto;
            padding: 48px 0;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 32px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-mark {
            display: grid;
            width: 48px;
            height: 48px;
            place-items: center;
            border: 1px solid rgba(15, 118, 110, 0.18);
            border-radius: 8px;
            background: var(--surface);
            box-shadow: var(--shadow);
            color: var(--teal);
            font-weight: 800;
            letter-spacing: 0;
        }

        h1,
        h2,
        h3,
        p {
            margin: 0;
        }

        h1 {
            font-size: clamp(2rem, 4vw, 4rem);
            line-height: 1.02;
            letter-spacing: 0;
        }

        .intro {
            max-width: 620px;
            margin-top: 14px;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.7;
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            margin: 30px 0;
        }

        .search-form label {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
        }

        .search-form input {
            width: 100%;
            min-height: 54px;
            padding: 0 18px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--surface);
            color: var(--ink);
            font-size: 1rem;
            outline: none;
            box-shadow: 0 10px 30px rgba(24, 34, 47, 0.08);
        }

        .search-form input:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.14);
        }

        .search-form button {
            min-height: 54px;
            padding: 0 24px;
            border: 0;
            border-radius: 8px;
            background: var(--teal);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 160ms ease, background 160ms ease;
        }

        .search-form button:hover {
            background: var(--teal-dark);
            transform: translateY(-1px);
        }

        .summary-panel {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(240px, 0.8fr);
            gap: 28px;
            align-items: stretch;
            margin-top: 24px;
            padding: 28px;
            border: 1px solid rgba(219, 229, 234, 0.92);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.86);
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }

        .place {
            color: var(--muted);
            font-size: 0.95rem;
            margin-bottom: 10px;
        }

        .temperature {
            margin-top: 20px;
            font-size: clamp(3.5rem, 10vw, 7rem);
            line-height: 0.9;
            font-weight: 800;
        }

        .condition {
            margin-top: 16px;
            color: var(--teal-dark);
            font-size: 1.25rem;
            font-weight: 700;
        }

        .metric-list {
            display: grid;
            gap: 12px;
            align-content: center;
        }

        .metric {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--surface-soft);
        }

        .metric span {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .metric strong {
            color: var(--ink);
            font-size: 1rem;
            text-align: right;
        }

        .section-title {
            margin: 36px 0 16px;
            font-size: 1.35rem;
        }

        .forecast-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 14px;
        }

        .forecast-day {
            min-height: 188px;
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--surface);
            box-shadow: 0 12px 28px rgba(24, 34, 47, 0.08);
        }

        .forecast-day h3 {
            margin-bottom: 14px;
            color: var(--teal-dark);
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .forecast-day p {
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.7;
        }

        .forecast-day strong {
            color: var(--ink);
        }

        .message {
            margin-top: 24px;
            padding: 16px 18px;
            border-left: 4px solid var(--rose);
            border-radius: 8px;
            background: #fff1f2;
            color: #9f1239;
        }

        .empty-state {
            margin-top: 24px;
            padding: 22px;
            border: 1px dashed var(--line);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.72);
            color: var(--muted);
            line-height: 1.6;
        }

        .search-results {
            display: grid;
            gap: 10px;
            margin-top: 16px;
        }

        .search-result {
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--surface);
        }

        @media (max-width: 760px) {
            .page {
                width: min(100% - 24px, 1080px);
                padding: 28px 0;
            }

            .topbar,
            .summary-panel {
                grid-template-columns: 1fr;
            }

            .topbar {
                display: block;
            }

            .brand {
                align-items: flex-start;
            }

            .search-form {
                grid-template-columns: 1fr;
            }

            .search-form button {
                width: 100%;
            }

            .summary-panel {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <header class="topbar">
            <div class="brand">
                <div class="brand-mark">WF</div>
                <div>
                    <h1>Weather Forecast</h1>
                    <p class="intro">Search a city and get the current weather plus a simple 7-day forecast.</p>
                </div>
            </div>
        </header>

        <form class="search-form" method="GET" action="<?php echo e(route('weather.index')); ?>">
            <label for="city">Enter City</label>
            <input id="city" type="text" name="city" value="<?php echo e(request('city')); ?>" placeholder="Example: Kuala Lumpur">
            <button type="submit">Search Weather</button>
        </form>

        <?php if(isset($weatherData)): ?>
            <?php if(isset($weatherData['error'])): ?>
                <p class="message"><?php echo e($weatherData['error']); ?></p>
            <?php else: ?>
                <section class="summary-panel" aria-label="Current weather">
                    <div>
                        <p class="place"><?php echo e($weatherData['city']); ?>, <?php echo e($weatherData['country']); ?></p>
                        <h2>Current Weather</h2>
                        <p class="temperature"><?php echo e($weatherData['temperature']); ?>&deg;C</p>
                        <?php
    $icon = match ($weatherData['condition']) {
        'Clear sky' => '☀️',
        'Partly cloudy' => '⛅',
        'Fog' => '🌫️',
        'Drizzle' => '🌦️',
        'Rain' => '🌧️',
        'Snow' => '❄️',
        'Rain showers' => '🌦️',
        'Thunderstorm' => '⛈️',
        'Thunderstorm with hail' => '⛈️',
        default => '🌡️',
    };
?>

<p class="condition"><?php echo e($icon); ?> <?php echo e($weatherData['condition']); ?></p>

                    </div>

                    <div class="metric-list">
                        <div class="metric">
                            <span>Wind Speed</span>
                            <strong><?php echo e($weatherData['wind_speed']); ?> km/h</strong>
                        </div>
                        <div class="metric">
                            <span>Forecast Days</span>
                            <strong><?php echo e(count($weatherData['forecast'])); ?> days</strong>
                        </div>
                    </div>
                </section>

                <h2 class="section-title">7 Day Forecast</h2>

                <section class="forecast-grid" aria-label="7 day forecast">
                    <?php $__currentLoopData = $weatherData['forecast']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="forecast-day">
                            <h3><?php echo e($day['date']); ?></h3>
                            <p><strong>Max:</strong> <?php echo e($day['max_temperature']); ?>&deg;C</p>
                            <p><strong>Min:</strong> <?php echo e($day['min_temperature']); ?>&deg;C</p>
                            <p><strong>Rain:</strong> <?php echo e($day['rain_chance']); ?>%</p>
                        <?php
    $dayIcon = match ($day['condition']) {
        'Clear sky' => '☀️',
        'Partly cloudy' => '⛅',
        'Fog' => '🌫️',
        'Drizzle' => '🌦️',
        'Rain' => '🌧️',
        'Snow' => '❄️',
        'Rain showers' => '🌦️',
        'Thunderstorm' => '⛈️',
        'Thunderstorm with hail' => '⛈️',
        default => '🌡️',
    };
?>

<p><strong>Condition:</strong> <?php echo e($dayIcon); ?> <?php echo e($day['condition']); ?></p>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </section>
            <?php endif; ?>
        <?php else: ?>
            <section class="empty-state">
                Enter a city name to see the latest weather forecast.
            </section>
        <?php endif; ?>

        <?php if(isset($forecastData)): ?>
            <h2 class="section-title">Forecast</h2>
            <section class="forecast-grid" aria-label="Coordinate forecast">
                <?php $__currentLoopData = $forecastData['forecast']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="forecast-day">
                        <h3><?php echo e($day['date']); ?></h3>
                        <p><strong>Max:</strong> <?php echo e($day['max_temperature']); ?>&deg;C</p>
                        <p><strong>Min:</strong> <?php echo e($day['min_temperature']); ?>&deg;C</p>
                        <p><strong>Rain:</strong> <?php echo e($day['rain_chance']); ?>%</p>
                        <p><strong>Condition:</strong> <?php echo e($day['condition']); ?></p>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>
        <?php endif; ?>

        <?php if(isset($searchResults)): ?>
            <h2 class="section-title">Search Results</h2>
            <section class="search-results">
                <?php $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="search-result"><?php echo e($result['name']); ?>, <?php echo e($result['country']); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
<?php /**PATH C:\Users\ARUVINTEN RAMANATAHN\Dropbox\laravel-beginner\resources\views/weather/index.blade.php ENDPATH**/ ?>