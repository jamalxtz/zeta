// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

let urlPadrao = $("#urlPadrao").val();
let url = urlPadrao+"models/monitor/ajax-monitor-model.php"


// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: buscarLabel("mes"),
    datasets: [{
      label: "Atendimentos",
      lineTension: 0.3,
      backgroundColor: "rgba(2,117,216,0.2)",
      borderColor: "rgba(2,117,216,1)",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 50,
      pointBorderWidth: 2,
      //data: buscarDados("mes",url),
      data: [id = 10000, 30162, 26263, 18394, 18287, 28682, 31274, 33259, 25849, 24159, 32651, 31984, 38451],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 40000,
          maxTicksLimit: 5
        },
        gridLines: {
          color: "rgba(0, 0, 0, .125)",
        }
      }],
    },
    legend: {
      display: false
    }
  }
});//Fim da função que monta o grafico

//**********************************************************************************************************************

//Função que busca os labels do grafico no banco de dados
function buscarLabel(parametro){
  if(parametro == "dia"){
    var labels = ["dom", "seg", "ter", "qua", "qui", "sex", "sab", "dom"];
    return labels;
  }else{
    var labels = ["jan", "fev", "mar", "abri", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"];
    return labels;
  }

}//FIM da função que busca os labels do grafico no banco de dados

//**********************************************************************************************************************

//Função que busca os dados do grafico no banco de dados
function buscarDados(parametro,url){

    var idUsuario = 62;

    $.ajax({
         url : url,
         type : 'post',
         data : {
              varFuncao : 'buscarDados', 
              idUsuario : idUsuario,
              parametro : parametro,       
         },
         dataType: 'json',
         beforeSend : function(){
            //alert(id+" \n "+ url);
         }
    })
    .done(function(msg){
        console.log(msg);
        alert(msg.id);
        return msg;

    })

    .fail(function(jqXHR, textStatus, msg){
        alert("Erro no retorno de dados: "+textStatus+"\n"+msg);
        console.log("Erro no retorno de dados: "+textStatus+"\n"+msg+"\n"+jqXHR);
    });

}//FIM da função que busca os dados do grafico no banco de dados




