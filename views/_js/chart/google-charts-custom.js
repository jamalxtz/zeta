// google.load("visualization", "1", {packages:["corechart"]});
// google.setOnLoadCallback(drawChart1);
// function drawChart1() {
//   var data = google.visualization.arrayToDataTable([
//     ['Year', 'Sales', 'Expenses'],
//     ['2004',  1000,      400],
//     ['2005',  1170,      460],
//     ['2006',  660,       1120],
//     ['2007',  1030,      540]
//   ]);

//   var options = {
//     title: 'Company Performance',
//     hAxis: {title: 'Year', titleTextStyle: {color: 'red'}}
//  };

// var chart = new google.visualization.PieChart(document.getElementById('grafico-despesas'));
//   chart.draw(data, options);
// }

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
          title: 'Despesas Mensal'
        };

        var chart = new google.visualization.PieChart(document.getElementById('grafico-despesas'));

        chart.draw(data, options);
      }

$(window).resize(function(){
  drawChart1();
  drawChartDespesas();
});

// Reminder: you need to put https://www.google.com/jsapi in the head of your document or as an external resource on codepen //