<?php
$this->registerJsFile('@web/js/chart.js', ['position' => \yii\web\View::POS_END]);
?>

<canvas id="statsChart" height="120"></canvas>

<?php
$this->registerJs("
    const ctx = document.getElementById('statsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: " . json_encode($labels) . ",
            datasets: [{
                label: 'Profit (USD)',
                data: " . json_encode($profit) . ",
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }
    });
", \yii\web\View::POS_READY);
?>
