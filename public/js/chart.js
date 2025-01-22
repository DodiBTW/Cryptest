document.addEventListener('DOMContentLoaded', () => {
    // Récupération des données initiales depuis les variables globales définies dans le PHP
    const chartData = window.initialChartData;
    const tokenName = window.tokenName;

    const config = {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Temps' } },
                y: { title: { display: true, text: 'Prix (USDC)' } }
            }
        }
    };

    const ctx = document.getElementById('tokenChart').getContext('2d');
    const tokenChart = new Chart(ctx, config);

    function loadChartData(timeframe) {
        fetch(`index.php?controller=Token&action=getChartData&timeframe=${timeframe}`)
            .then(response => response.json())
            .then(data => {
                tokenChart.data.labels = data.labels;
                tokenChart.data.datasets[0].data = data.values;
                tokenChart.update();
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Charger les données initiales
    loadChartData('1D');

    // Ajouter les écouteurs d'événements aux boutons de sélection de timeframe
    document.querySelectorAll('.time-options button').forEach(button => {
        button.addEventListener('click', () => {
            const timeframe = button.id;
            loadChartData(timeframe);
        });
    });
});
