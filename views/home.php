<?php
$title = "Accueil - Cryptest";
include 'views/layouts/header.php';
?>

<div class="container1">
	<div class="chart-section">
		<div class="chart-header">
			<span class="pair">RAY / SOL</span>
			<span class="date">25/01/22 08:01</span>
			<div class="time-options">
				<button>15m</button>
				<button>1H</button>
				<button>4H</button>
				<button>1D</button>
				<button>1W</button>
			</div>
		</div>
		<div class="chart">
			<div class="chart-placeholder">
				<p>Graphique ici</p>
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
					<span>SOL</span>
					<input type="text" placeholder="0.00">
				</div>
			</div>
			<div class="swap-arrow">↓</div>
			<div class="swap-input">
				<label>To</label>
				<div class="input-container">
					<span>RAY</span>
					<input type="text" placeholder="0.00">
				</div>
			</div>
			<button class="swap-button">Swap</button>
		</div>
	</div>
</div>