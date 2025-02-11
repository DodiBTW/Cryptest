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
                <button id="5m"><a href="/?page=<?php echo htmlspecialchars($token['name'])?>&option=5">5m</a></button>
                <button id="15m"><a href="/?page=<?php echo htmlspecialchars($token['name'])?>&option=15">15m</a></button>
                <button id="30m"><a href="/?page=<?php echo htmlspecialchars($token['name'])?>&option=30">30m</a></button>
                <button id="1H"><a href="/?page=<?php echo htmlspecialchars($token['name'])?>&option=60">1H</a></button>
                <button id="4H"><a href="/?page=<?php echo htmlspecialchars($token['name'])?>&option=240">4H</a></button>
            </div>
        </div>
        <div class="chart">
            <div class="chart-placeholder">
                <canvas id="tokenChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <form action="/?page=transaction" method="POST" class="swap-section">
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
                            <input type="number" id="amount" name="amount" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="text" value="<?php echo htmlspecialchars($token['name']); ?>" name="name" class="invisibleInput">
        <div class="action-buttons">
            <button class="swap-button buy-button" type="submit" name="action" value="buy">Acheter</button>
            <button class="swap-button sell-button" type="submit" name="action" value="sell">Vendre</button>
        </div>
    </form>
