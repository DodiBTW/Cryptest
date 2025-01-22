<?php
$title = "Accueil - Cryptest";
include 'views/layouts/header.php';
?>

<script>
    window.initialChartData = {
        labels: <?php echo json_encode($chartLabels); ?>,
        datasets: [{
            label: '<?php echo htmlspecialchars($token['name']); ?> Price',
            data: <?php echo json_encode($chartValues); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            fill: true,
            tension: 0.1
        }]
    };

    window.tokenName = '<?php echo htmlspecialchars($token['name']); ?>';
</script>

<script src="public/js/chart.js"></script>

<div class="container1">
    <div class="chart-section">
        <div class="chart-header">
            <span class="pair"><?php echo htmlspecialchars($token['name'])?></span>
            <div class="time-options">
                <button id="15m">15m</button>
                <button id="1H">1H</button>
                <button id="4H">4H</button>
                <button id="1D">1D</button>
                <button id="1W">1W</button>
            </div>
        </div>
        <div class="chart">
            <div class="chart-placeholder">
                <canvas id="tokenChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="swap-section">
        <div class="swap-header">
            <span>Buy</span>
            <span>Settings (0.5%)</span>
        </div>
        <div class="swap-box">
            <div class="swap-input">
                <label>From</label>
                <div class="input-container">
                    <span>USDC</span>
                    <input type="text" placeholder="0.00">
                </div>
            </div>
            <div class="swap-arrow">↓</div>
            <div class="swap-input">
                <label>To</label>
                <div class="input-container">
                    <span><?php echo htmlspecialchars($token['name']) ?></span>
                    <input type="text" placeholder="0.00">
                </div>
            </div>
            <button class="swap-button">Swap</button>
        </div>
    </div>
</div>
