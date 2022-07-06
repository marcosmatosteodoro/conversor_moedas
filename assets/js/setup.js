var ctx = document.getElementById('canvas').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels_grafico,
        datasets: [{
            label: titulo_grafico,
            data: data_grafico,
            backgroundColor: 'transparent',
            borderColor: '#0d6efd',
            borderWidth: 1,
            hidden: false
        }]
    },
    options: {
        elements:{
            line:{
                tension:.5
            }
        },
        scales: {
            y: {
                beginAtZero: false,

            }
        }
    }
});

$("#valor1").maskMoney({ allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
$("#valor2").maskMoney({ allowNegative: true, thousands:'.', decimal:',', affixesStay: false});