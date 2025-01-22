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
            <span class="pair"><?php echo htmlspecialchars($token['name']) ?></span>
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
        <div>
            <div class="swap-header">
                <span>Buy/Sell</span>
                <span>Settings (0.5%)</span>
            </div>
            <div class="swap-options">
                <div class="swap-box">
                    <div class="swap-input">
                        <label for="amount">Montant</label>
                        <div class="input-container">
                            <span><?php echo htmlspecialchars($token['name']); ?></span>
                            <input type="text" id="amount" placeholder="0.00">
                        </div>
                    </div>

                    <div class="position-selector">
                        <label>Position</label>
                        <div class="toggle-switch">
                            <input type="radio" id="long" name="position" value="long" checked>
                            <label for="long" class="toggle-label">Long</label>
                            <input type="radio" id="short" name="position" value="short">
                            <label for="short" class="toggle-label1">Short</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="action-buttons">
            <button class="swap-button buy-button">Acheter</button>
            <button class="swap-button sell-button">Vendre</button>
        </div>
    </div>