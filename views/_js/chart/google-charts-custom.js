
function GraficoDespesaMensal(arrayGraficoDespesas){
  google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartDespesas);

        function drawChartDespesas() {

          var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Work',     150.20],
            ['Eat',      600.30],
            ['Commute',  158.69],
            ['Watch TV', 87.8],
            ['Sleep',    12.50]
          ]);

          var options = {
            title: 'Despesas Mensal',
            legend: { position: 'bottom', alignment: 'midlle' },
            is3D:true,
            height:400,
          };

          var chart = new google.visualization.PieChart(document.getElementById('grafico-despesas'));

          chart.draw(data, options);
        }

  $(window).resize(function(){
    drawChartDespesas();
  });
}

// Reminder: you need to put https://www.google.com/jsapi in the head of your document or as an external resource on codepen //