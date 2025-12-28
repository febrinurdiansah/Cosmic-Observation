<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['NASA_API_KEY']; 
$date = $_GET['date'] ?? date('Y-m-d');

$url = "https://api.nasa.gov/planetary/apod?api_key=$apiKey&date=$date&thumbs=true";
$response = @file_get_contents($url);
$data = $response ? json_decode($response, true) : null;

$neoUrl = "https://api.nasa.gov/neo/rest/v1/feed?start_date=$date&end_date=$date&api_key=$apiKey";
$neoResponse = @file_get_contents($neoUrl);
$neoData = $neoResponse ? json_decode($neoResponse, true) : null;
$asteroids = $neoData['near_earth_objects'][$date] ?? [];

if (!$data || isset($data['error'])) {
    $data = [
        "title" => "Data Not Available",
        "explanation" => "Astronomy Picture of the Day tidak tersedia untuk tanggal ini.",
        "url" => "NOT_FOUND",
        "date" => $date,
        "media_type" => "image"
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmic Observation</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>

<div class="app">
    <!-- LEFT INFO PANEL -->
    <section class="info-panel">
    <div class="info-header">
        <span class="label">COSMIC • OBSERVATION</span>
        <h1 class="title"><?= htmlspecialchars($data['title']) ?></h1>
        <p class="subtitle"><?= htmlspecialchars($data['date']) ?></p>
    </div>

<div class="panel-switch" style="margin: 15px 0;">
        <button class="tab-btn active" onclick="switchTab('desc-panel', this)">DESCRIPTION</button>
        <button class="tab-btn" onclick="switchTab('asteroid-panel', this)">
            ASTEROID WATCH <span><?= count($asteroids) ?></span>
        </button>
    </div>

    <div class="info-body">
        <div class="tab-content-wrapper">
            <div id="desc-panel" class="tab-content active">
                <p class="description" style="font-size: 14px; line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($data['explanation'])) ?>
                </p>
            </div>

        <div id="asteroid-panel" class="tab-content">
            <div class="asteroid-list">
                <?php if (!empty($asteroids)): ?>
                    <?php foreach (array_slice($asteroids, 0, 5) as $neo): 
                        $isHazard = $neo['is_potentially_hazardous_asteroid'];
                    ?>
                        <div class="ast-card <?= $isHazard ? 'hazard' : '' ?>">
                            <div class="ast-info">
                                <span class="ast-name"><?= $neo['name'] ?></span>
                                <span class="ast-size">Size: <?= round($neo['estimated_diameter']['meters']['estimated_diameter_max']) ?>m</span>
                            </div>
                            <div class="ast-status">
                                <span class="dist"><?= number_format($neo['close_approach_data'][0]['miss_distance']['kilometers'] / 1000000, 1) ?>M km</span>
                                <span class="status-tag"><?= $isHazard ? '⚠ HAZARD' : 'SAFE' ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data">No near-earth objects tracked for this date.</p>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <canvas id="starfield" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; pointer-events: none;"></canvas>

    <div class="info-footer">
        <div class="footer-container">
            <div class="meta-group">
                <div class="meta-item">
                    <span class="label">ACTION</span>
                    <a href="<?= htmlspecialchars($data['hdurl'] ?? $data['url']) ?>" 
                    download="NASA_APOD_<?= $date ?>.jpg" 
                    target="_blank" class="save-btn">
                    💾 SAVE WALLPAPER
                    </a>
                </div>
                <div class="meta-divider"></div>
                <div class="meta-item">
                    <span class="label">MEDIA</span>
                    <strong><?= strtoupper($data['media_type']) ?></strong>
                </div>
                <div class="meta-divider"></div>
                <div class="meta-item">
                    <span class="label">HD</span>
                    <strong><?= isset($data['hdurl']) ? 'YES' : 'NO' ?></strong>
                </div>
            </div>

            <form class="date-picker">
                <input type="date" name="date" id="cosmic-date" value="<?= $date ?>" max="<?= date('Y-m-d') ?>">
                <button type="submit">EXPLORE</button>
            </form>
        </div>
    </div>

    </section>


    <!-- RIGHT VISUAL -->
    <section class="visual-panel">
        <?php if ($data['url'] === 'NOT_FOUND'): ?>
            <div class="not-found-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="#f5c542" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="22" y1="2" x2="2" y2="22"></line>
                </svg>
                <p>VISUAL DATA DELETED OR NOT CAPTURED</p>
            </div>
        <?php elseif ($data['media_type'] === 'image'): ?>
            <img src="<?= htmlspecialchars($data['url']) ?>" alt="APOD" id="mainImg" onclick="openModal('<?= htmlspecialchars($data['hdurl'] ?? $data['url']) ?>')">
        <?php else: ?>
            <iframe src="<?= htmlspecialchars($data['url']) ?>" frameborder="0" allowfullscreen></iframe>
        <?php endif; ?>
    </section>

    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="fullImg">
        <div id="caption"></div>
    </div>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>
