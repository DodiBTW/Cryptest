document.addEventListener('DOMContentLoaded', () => {

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

    loadChartData('1D');

    document.querySelectorAll('.time-options button').forEach(button => {
        button.addEventListener('click', () => {
            const timeframe = button.id;
            loadChartData(timeframe);
        });
    });
});
