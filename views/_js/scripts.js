/*!
* Start Bootstrap - SB Admin v6.0.0 (https://startbootstrap.com/templates/sb-admin)
* Copyright 2013-2020 Start Bootstrap
* Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-sb-admin/blob/master/LICENSE)
*/
(function($) {
"use strict";
  // Add active state to sidbar nav links
  var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
    $("#layoutSidenav_nav .sb-sidenav a.nav-link").each(function() {
        if (this.href === path) {
            $(this).addClass("active");
        }
    });

// Toggle the side navigation
$("#sidebarToggle").on("click", function(e) {
    e.preventDefault();
    $("body").toggleClass("sb-sidenav-toggled");
});
})(jQuery);

//***********************************************************************************************************************

//Ativa o menu de tab que aparece no cadastro de novos usuarios EX: Dados pessoais, endereço, etc
  $(function () {
    $('#myTab a:first').tab('show');
  })

//Ativa os tooltips que aparecem em cima dos botoes para indicar a ação
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

//***********************************************************************************************************************
$(document).ready(function(){
  //Alterna a mascara entre cpf e cnpj 
  //OBS:  .cpfcnpj ele pega os dados por essa classe
  var options = {
      onKeyPress: function (cpf, ev, el, op) {
          var masks = ['000.000.000-000', '00.000.000/0000-00'];
          $('.cpfcnpj').mask((cpf.length > 14) ? masks[1] : masks[0], op);
      }
  }
  $('.cpfcnpj').length > 11 ? $('.cpfcnpj').mask('00.000.000/0000-00', options) : $('.cpfcnpj').mask('000.000.000-00#', options);

  //Mascara de dinheiro, utilizado no Finances
  $('.mask-money').mask('#.##0,00', {reverse: true});
});

//***********************************************************************************************************************

function buscarCep(){
    var cep = $("#cep").val();
    var cepTratado = cep.replace(/\D+/g, '' );
    var url = "http://viacep.com.br/ws/"+cepTratado+"/json/"
    var cepIcone = document.getElementById("cepIcone");
    //alert(url);
    if(cepTratado.length != 8){
        alert("Por favor informar um cep valido com 7 dígitos!");
        return;
    }

        $.ajax({
         url : url,
         type : 'get',
         data : {
              //varFuncao : 'buscarAtendimento', 
              //id : id,       
         },
         dataType: 'json',
         beforeSend : function(){
            cepIcone.classList.add("fa-spinner");
         }
    })
    .done(function(msg){
        //console.log(msg);
        //alert(msg.logradouro);

        var cepIcone = document.getElementById("cepIcone");
        cepIcone.classList.add("fa-search");
        
        $("#logradouro").val(msg.logradouro);
        $("#complemento").val(msg.complemento);
        $("#bairro").val(msg.bairro);
        $("#estado").val(msg.uf);
        $("#cidade").val(msg.localidade);
    })
    .fail(function(jqXHR, textStatus, msg){
        alert("Erro JSON ao buscar o cep: "+textStatus+"\n"+msg);
        console.log("Erro JSON ao buscar o cep: "+textStatus+"\n"+msg+"\n"+jqXHR);
    });
}

//************************************************************************************************************************
//Função que troca a cor de fundo do site (OBSOLETE - SE NÃO FOR UTILIZAR, APAGAR ESSA FUNÇÃO)
$('#colorPicker').on('change', function() {
  //alert(this.value);
  //$('.bg-color').css('background-color', this.value);
  $('.bg-color').css('background-image', 'linear-gradient(to bottom left, '+this.value+', rgba(0,0,0,0)');
});

//************************************************************************************************************************
//Função utilizada para Reportar um erro (Envia email)

function ReportarErro(){ //Pega o ofrmulario pelo ID

  let erroDetalhes = $('#erroDetalhes').val();
  if (erroDetalhes == ""){
    alert("Informe um erro válido!");
    $('#erroDetalhes').focus();
    return;
  }

  //desabilita o botão de Envio
  document.getElementById("btnEnviarErro").disabled = true;
  $("#btnEnviarErro").text("Enviando...");
  
  let url = $('#url').val()+"controllers/enviarEmail-controller.php";

  //alert( $('#email').val() +" \n "+ url);
  
  $.ajax({
    url: url,
    type: 'post',
    dataType: 'html',
    data: {
      'url': $('#url').val(),
      'erroDetalhes': erroDetalhes
    }

  }).done(function(data){
    alert(data);
    document.location.reload(true);
  });
  
};


//************************************************************************************************************************
let pagina = "";
if(document.getElementById('pagina')){
  pagina = document.getElementById('pagina').value;
}

const WallpaperRandomico = async () => {
  const res = await fetch(
    "https://api.pexels.com/v1/curated",
  {
    headers: {
      Authorization: '563492ad6f91700001000001ca4393a683e6484b9c664039790ef878',
    },
  }
);
  const responseJson = await res.json();
  // return responseJson.photos;
  var urlImagem =  ( responseJson.photos[0].src.landscape);
  //Define o papel de parede
  document.body.style.backgroundImage = "url("+urlImagem+")";
  var element = document.getElementsByTagName("body")[0];
  element.classList.add("bg-imagem");
};

if(pagina =='login'){
  //WallpaperPorTurno();
  WallpaperRandomico();
}

//alterar plano de fundo da tela de login
function WallpaperPorTurno(){
  // Obtém a data/hora atual
  var data = new Date();
  let url = document.getElementById('url').value;

  //var hora    = data.getHours();   // 0-23
  var hora    = data.getHours();   // 0-23
  var min     = data.getMinutes(); // 0-59

  //alert(hora);

  //Nascer do sol
  if(hora >= 5 && hora < 7){
    //alert("url('"+url+"rodape_1782040144.jpg')");
    document.body.style.backgroundImage = "url('"+url+"sunshine.jpg')";
    var element = document.getElementsByTagName("body")[0];
    element.classList.add("bg-imagem");
  }
  
  //Diurno
  if(hora >= 7 && hora < 17){
    //alert("url('"+url+"rodape_1782040144.jpg')");
    document.body.style.backgroundImage = "url('"+url+"day.jpg')";
    var element = document.getElementsByTagName("body")[0];
    element.classList.add("bg-imagem");
  }

  //Por do sol
  if(hora >= 17 && hora < 19){
    //alert("url('"+url+"rodape_1782040144.jpg')");
    document.body.style.backgroundImage = "url('"+url+"sunset.jpg')";
    var element = document.getElementsByTagName("body")[0];
    element.classList.add("bg-imagem");
  }

  //Noturno
  if(hora >= 19 && hora < 24 || hora >= 0 && hora < 5){
    //alert("url('"+url+"rodape_1782040144.jpg')");
    document.body.style.backgroundImage = "url('"+url+"night.jpg')";
    var element = document.getElementsByTagName("body")[0];
    element.classList.add("bg-imagem");
  }
};



