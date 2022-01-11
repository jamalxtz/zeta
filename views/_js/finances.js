//*********************************************************************************************************************
//*******************************************   FUNÇÕES GLOBAIS   *****************************************************                  
//*********************************************************************************************************************
//#region DECLARAÇÃO DE VARIÁVEIS E FUNÇÕES GLOBAIS--------------------------------------------------------------------

//Configura um mini alerta do plugin sweetalert2 (https://sweetalert2.github.io/#examples), que é exibido através da função:
// Toast.fire({
//   icon: 'success',
//   title: 'Signed in successfully'
// })
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: false,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

window.onload = function() {
  //Verifica qual página está sendo carregada (existe um input em cada página com o nome dela no value)
  if ( $('#pagina').val() == "despesas"){
    //Criar função aqui para pegar a data de referencia, fazer uma consulta na tabela de configurações e então verificar a ultima data de referencia
    /*Aparentemente os métodos não são carregados corretamente quando estão em sequencia nessa função
    *Pensei em criar um método de rotina que engloba todos os outros, acredito que isso facilitaria até mesmo para colocar um loader
    */
    PreencherDataReferencia();
    
  }
  else if ( $('#pagina').val() == "editarDespesa"){
    //Criar função aqui para pegar a data de referencia, fazer uma consulta na tabela de configurações e então verificar a ultima data de referencia
    PreencherCamposEditarDespesa();
  }
  
};

//#endregion

//#region FUNÇÕES DE CONVERSÃO-----------------------------------------------------------------------------------------

//Recebe a data no formato DD/MM/AAAA e retorna no padrão YYYY-MM-DD
function FormataDataPadraoAmericano(data) {
  var dia  = data.split("/")[0];
  var mes  = data.split("/")[1];
  var ano  = data.split("/")[2];

  return ano + '-' + ("0"+mes).slice(-2) + '-' + ("0"+dia).slice(-2);
  // Utilizo o .slice(-2) para garantir o formato com 2 digitos.
}//FormataDataPadraoAmericano

//Recebe a data no formato YYYY-MM-DD HH:MM:SS e retorna no padrão YYYY-MM-DD aceita pelos inputs do tipo date
function FormataDataBancoDeDadosParaInput(data) {
  data = data.substring(0, 10);
  var ano  = data.split("-")[0];
  var mes  = data.split("-")[1];
  var dia  = data.split("-")[2];

  return ano + '-' + ("0"+mes).slice(-2) + '-' + ("0"+dia).slice(-2);
  // Utilizo o .slice(-2) para garantir o formato com 2 digitos.
}//FormataDataBancoDeDadosParaInput

//Recebe a data no formato YYYY-MM-DD e retorna no padrão YYYY-MM aceita pelos inputs do tipo Month (data de referência)
function FormataDataParaInputMonth(data) {
  data = data.substring(0, 10);
  var ano  = data.split("-")[0];
  var mes  = data.split("-")[1];
  var dia  = data.split("-")[2];

  return ano + '-' + ("0"+mes).slice(-2)
  // Utilizo o .slice(-2) para garantir o formato com 2 digitos.
}//FormataDataParaInputMonth

//Recebe a data no formato YYYY-MM-DD e retorna no padrão DD/MM/AAAA 
function FormatarDataPadraoBrasileiro(data){
  //Troca os traços por vírgulas
  data = data.replace(/-/g, ",");
  //Formata as datas e valores para o padrão brasileiro
  let dataFormatada = new Date(data);
  dataFormatada = dataFormatada.toLocaleDateString();
  return dataFormatada;
}//FormatarDataPadraoBrasileiro

//Retorna a data atual no padrão Americao YYYY-MM-DD (aceito pelos inputs tipo date)
function DataAtual(){
  var today = new Date();
  var dy = today.getDate();
  var mt = today.getMonth()+1;
  var yr = today.getFullYear();
  return yr+"-"+mt+"-"+dy;
}//DataAtual

//Recebe o valor no formato 1.222.222,56 e retorna no padrão 1222222.56
function ConverterRealParaFloat(valor){
  if(valor === ""){
      valor =  0;
  }else{
      // valor = valor.replace("R$ ","");
      valor = valor.replace(".","");
      valor = valor.replace(",",".");
      valor = parseFloat(valor);
  }
  return valor;
}//ConverterRealParaFloat

//Recebe o valor no formato 1222222.56 e retorna no padrão (R$ 1.222.222,56) ou (1.222.222,56)
function ConverterValorParaRealBrasileiro(valor, utilizarRS = true){
  if(utilizarRS === true){
    //Com R$
    valor = valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' })
  }else{
    //Cem R$
    valor = valor.toLocaleString('pt-br', {minimumFractionDigits: 2});
  }
  return valor;
}//ConverterValorParaRealBrasileiro

//#endregion

//#region CADASTRO DE CATEGORIA----------------------------------------------------------------------------------------

//Função utilizada para cadastrar uma nova categoria
$("#formCadastrarCategoriaNC").on("submit", function (event) { 
  event.preventDefault();

  let url = $('#idURL').val();
  let requisicao = "incluirCategoria";
  let userID = $('#userID').val(); // ID do usuário logado
  let descricao = $("#txtDescricaoCategoriaNC").val();
  let tipo =  $("#selTipoCategoriaNC").val();

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
    url : url,
    type : 'post',
    data : {
      requisicao : requisicao,
      userID : userID,  
      descricao : descricao,
      tipo : tipo,    
    },
    dataType: 'json',
    beforeSend : function(){
      //alert(requisicao+" \n "+ url );
    }
  })
  .done(function(msg){
    //alert(msg.mensagem);
    if (msg.success == true){
      //Fecha o modal
      $('#modalCadastrarCategoria').modal('toggle');

      $('select').append($('<option>', {
        value: msg[0].id,
        text: descricao
      }));
      //Exibe mensagem
      Toast.fire({
        icon: 'success',
        title: msg.mensagem
      })
    }else{
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
    }
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro ao cadastrar categoria: "+"\n"+jqXHR.responseText);
    console.log("Erro ao cadastrar categoria: "+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados


  //Utilizo esse return false, porque evita do formulário ser submetido, dessa forma a página não é carregada
  return false;
}); //FIM da função cadastrar uma nova categoria

//#endregion

//*********************************************************************************************************************
//******************************************   INCLUIR DESPESAS   *****************************************************                  
//*********************************************************************************************************************
//#region GERAÇÃO DE PARCELAS E DEMAIS FUNÇÕES DO CABEÇALHO DAS DESPESAS-----------------------------------------------

//Função utilizada para gerar as parcelas ao criar uma despesa (Validado - Falta revisar os comentários)
// $('#btnGerarParcelasND').click(function() { //Não utilizo mais essa forma, pois a submissão do formulário já faz a validação dos campos
$("#formCabecalhoDespesaND").on("submit", function (event) { 
  event.preventDefault();

  //Apagar esse bloco após os testes
  // if($("#txtDescricaoND").val() == "teste"){
  //   $('#txtDescricaoND').val("Teste Gerar Parcelas");
  //   // $('#selCategoriaND').val() = "";
  //   $('#txtVencimentoND').val("2021-12-12");
  //   $('#txtValorND').val("150,00");
  //   $('#txtParcelasND').val("10");
  // }
  
  let valorTotal = ConverterRealParaFloat($("#txtValorND").val());
  let valorEntrada = parseFloat($("#txtEntradaND").val()).toFixed(2);//Atualmente esse valor de entrada não é utilizado, deixei aqui só para usos futuro
  let parcelas = parseInt($("#txtParcelasND").val());
  let codCategoria =  parseInt($("#selCategoriaND").val());
  let codigoDeBarras =  "";
  let observacoes =  "";
  //let melhorDia = parseInt($("#melhorDia").val());
  let melhorDia = $("#txtVencimentoND").val().replace(/-/g, ",");

  if ((valorTotal) && (valorEntrada) && (parcelas)) {
    // ENCONTRA O VALOR LÍQUIDO
    let valorLiquido = parseFloat(valorTotal - valorEntrada);
    // CALCULA A PARCELA
    let valorParcela = parseFloat(valorLiquido / parcelas);
    let table = '';
    let par = 1;
    // ENCONTRA A DATA ATUAL
    let hoje = new Date();
    // CRIA A DATA NO MÊS SEGUINTE COM O MELHOR DIA

    //Antes recebia apenas o melhor dia e com base nesse dia que era calculado as parcelas
    //let primeiraParcela = new Date(hoje.getFullYear(), hoje.getMonth() + 1, melhorDia);
    let primeiraParcela = new Date(melhorDia);

    for(i = 0; i < parcelas; i++) {
      //Verifica se tem diferença do valor informado com a soma de todas as parcelas e insere os centavos de diferença na última parcela
      //if(i == parcelas - 1){
      //Verifica se houve arredondamento para menos
      //   alert("soma de todas as parcelas: " + valorParcela * parcelas);
      //   alert("Valor total: " + valorTotal);
      //   if((valorParcela * parcelas) < valorTotal){
          
      //     valorParcela = valorParcela + (valorTotal - (valorParcela * parcelas))
      //   }
      // }

      //Faz a inclusção dos valores na tabela
      table += '<tr>'
      table += '<td>' + par  + '</td>';//Número da parcela
      table += '<td class="hidden">' + par  + '</td>';//Descrição
      table += '<td>' + primeiraParcela.toLocaleDateString() + '</td>';//Vencimento
      table += '<td>' + ConverterValorParaRealBrasileiro(valorParcela) + '</td>';//Valor
      table += '<td class="hidden">' + codCategoria  + '</td>';//Categoria
      table += '<td class="hidden">' + codigoDeBarras  + '</td>';//Categoria
      table += '<td class="hidden">' + observacoes  + '</td>';//Categoria
      table += '</tr>';
      par++;
      primeiraParcela.setMonth(primeiraParcela.getMonth() + 1); // AUMENTA UM MÊS      
    }
    $('#tabelaParcelasND tbody').html(table);
  }

  $('#tabelaParcelasND').focus(); //Não está funcionando, procurar uma alternativa para esse evento

  //Utilizo esse return false, porque evita do formulário ser submetido, dessa forma a página não é carregada
  return false;
}); //FIM da função utilizada para gerar as parcelas ao criar uma despesa

//Função que executa os eventos do botão chkDespesaFixaND 
$('#chkDespesaFixaND').click(function() {
  let chkDespesaFixa = document.getElementById('chkDespesaFixaND');

  if(chkDespesaFixa.checked) {//Ao ativar o cadastro de despesa fixa.
    //Apaga todas as linhas da tabela de parcela 
    $("#tabelaParcelasBodyND tr").remove();
    $('#txtParcelasND').prop('readonly', true);
    $('#btnGerarParcelasND').prop('disabled', true);
    $('#btnCollapseCriarAlterarParcelaND').prop('disabled', true);
    $('#collapseCriarAlterarParcelaND').collapse("hide");

  } else {//Ao desabilitar o cadastro de despesa fixa.
    $('#txtParcelasND').prop('readonly', false);
    $('#btnGerarParcelasND').prop('disabled', false);
    $('#btnCollapseCriarAlterarParcelaND').prop('disabled', false);
  }
})//Evento Click chkDespesaFixaND

//#endregion

//#region INCLUSÃO E ALTERAÇÃO DE PARCELAS-----------------------------------------------------------------------------

//Função utilizada para incluir ou editar parcelas individualmente (validado)
//$('#btnIncluirAlterarParcelaND').click(function() { //Não utilizo mais essa forma, pois a submissão do formulário já faz a validação dos campos
$("#formParcelaDespesaND").on("submit", function (event) { 
  event.preventDefault();
  //Verifica se o usuário está incluindo ou alterando uma parcela
  let numeroParcela = $('#txtNumeroParcelaND').val();

  if(numeroParcela == ""){ // Modo de Inclusão
    IncluirParcela();
  }else{ // Modo de Alteração
    AlterarParcela(numeroParcela);
    LimparCamposIncluirDespesa(true);
    $('#collapseCriarAlterarParcelaND').collapse("hide");
  }
  //Utilizo esse return false, porque evita do formulário ser submetido, dessa forma a página não é carregada
  return false;
}); //FIM da função utilizada para incluir ou editar parcelas individualmente

//Faz a inclusão de novas parcelas
function IncluirParcela(){
  //Captura os valores dos campos
  //let tabelaDeParcelas = document.getElementById("tabelaParcelasND");
  let tabelaDeParcelas = document.getElementById("tabelaParcelasBodyND");
  let numeroDaParcela = $('#tabelaParcelasND tr').length;
  let vencimento = $("#txtVencimentoParcelaND").val();
  let valor = ConverterRealParaFloat($("#txtValorParcelaND").val());
  let descricao = document.getElementById("txtDescricaoParcelaND").value;
  let codigoCategoria = parseInt($("#selCategoriaParcelaND").val());
  let codigoDeBarras = document.getElementById("txtCodigoDeBarrasParcelaND").value;
  let observacoes = document.getElementById("txtObservacoesParcelaND").value;
  let novaLinha = tabelaDeParcelas.insertRow(-1);
  let novaCelula;

  //Formata as datas e valores para o padrão brasileiro
  vencimento = FormatarDataPadraoBrasileiro(vencimento);
  valor = valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' })

  //Insere os valores na tabela
  novaCelula = novaLinha.insertCell(0);
  novaCelula.innerHTML = numeroDaParcela;

  novaCelula = novaLinha.insertCell(1);
  novaCelula.innerHTML = descricao;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(2);
  novaCelula.innerHTML = vencimento;

  novaCelula = novaLinha.insertCell(3);
  novaCelula.innerHTML = valor;

  novaCelula = novaLinha.insertCell(4);
  novaCelula.innerHTML = codigoCategoria;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(5);
  novaCelula.innerHTML = codigoDeBarras;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(6);
  novaCelula.innerHTML = observacoes;
  novaCelula.className = "hidden";
  
  //Bruno Verificar se compensa inserir um botão para excluir as parcelas ou então subir e realizar a exclusão
  // novaCelula = novaLinha.insertCell(3);
  // novaCelula.style.backgroundColor = cortabela;
  // novaCelula.innerHTML = '<input type="button" value="X" onclick="deleteRow(this)"/>';
  
  //Limpa o campo de descrição
  $('#tabelaParcelasND tr').val("");
  //Exibe mensagem
  Toast.fire({
    icon: 'info',
    title: "Parcela incluída."
  })
}//IncluirParcela

//Faz a alteração de parcelas
function AlterarParcela(numeroParcela){
  //Obtém pos valores dos campos que serão incluídos nos campos
  let descricao = $('#txtDescricaoParcelaND').val();
  let categoria = $('#selCategoriaParcelaND').val();
  let parcela = $('#txtNumeroParcelaND').val();
  let vencimento = $('#txtVencimentoParcelaND').val();
  let valor = $("#txtValorParcelaND").val();
  let codigoDeBarras = $('#txtCodigoDeBarrasParcelaND').val();
  let observacoes = $('#txtObservacoesParcelaND').val();

  //Formata as datas e valores para o padrão brasileiro
  vencimento = FormatarDataPadraoBrasileiro(vencimento);
  valor = ConverterRealParaFloat(valor);
  valor = ConverterValorParaRealBrasileiro(valor)

  /*PONTO CRÍTICO:
  *Apesar de ter colocado para funcionar, não entendi muito bem como funciona essa alteração de células da tabela
  *Para executar essa função, utilizei o JQuery, ele seleciona os elementos da tabela utilizando o comando eq(), conforme abaixo:
  *   td:eq(2)  => Esse comando corresponde a coluna onde a célula está localizada
  *   $("#tabelaParcelasND tbody tr:eq("+ (numeroParcela - 1) +")")  => Esse comando corresponde a linha, para encontrar essa linha eu pego o número da parcela que fica em um campo oculto
  *Não gostei nem um pouco dessa solução, porém fiz varias pesquisas e não consegui encontrar nenhuma outra melhor */
  $('td:eq(1)', $("#tabelaParcelasND tbody tr:eq("+ (numeroParcela - 1) +")")).text(descricao);
  $('td:eq(2)', $("#tabelaParcelasND tbody tr:eq("+ (numeroParcela - 1) +")")).text(vencimento);
  $('td:eq(3)', $("#tabelaParcelasND tbody tr:eq("+ (numeroParcela - 1) +")")).text(valor);
  $('td:eq(4)', $("#tabelaParcelasND tbody tr:eq("+ (numeroParcela - 1) +")")).text(categoria);
  $('td:eq(5)', $("#tabelaParcelasND tbody tr:eq("+ (numeroParcela - 1) +")")).text(codigoDeBarras);
  $('td:eq(6)', $("#tabelaParcelasND tbody tr:eq("+ (numeroParcela - 1) +")")).text(observacoes);

  //Exibe mensagem
  Toast.fire({
    icon: 'info',
    title: "Parcela alterada."
  })
  
}//AlterarParcela

$('#btnCancelarInclusaoParcelaND').click(function() { 
  LimparCamposIncluirDespesa(true);
  $('#collapseCriarAlterarParcelaND').collapse("hide");
});

//Ao clicar nas linhas da tabela de despesa os dados da parcela sobem para serem editados
$("#tabelaParcelasND tbody").on('click', 'tr', function () {
  $('#collapseCriarAlterarParcelaND').collapse("show");

  let parcela = $('td:eq(0)', this).text().trim();
  let descricao = $('td:eq(1)', this).text().trim();
  let vencimento = $('td:eq(2)', this).text().trim();
  let valor = $('td:eq(3)', this).text().trim();
  let categoria = $('td:eq(4)', this).text().trim();
  let codigoDeBarras = $('td:eq(5)', this).text().trim();
  let observacoes = $('td:eq(6)', this).text().trim();
  //Converte a data de vencimento para o padrão americano, o input do tipo date só recebe datas no formato YYYY-MM-DD
  vencimento = FormataDataPadraoAmericano(vencimento);
  //Remove o R$ se tiver
  valor = valor.replace("R$","");
  valor = valor.trim();
  valor = ConverterValorParaRealBrasileiro(valor, false);

  $('#txtDescricaoParcelaND').val(descricao);
  $('#selCategoriaParcelaND').val(categoria);
  $('#txtNumeroParcelaND').val(parcela);
  $('#txtVencimentoParcelaND').val(vencimento);
  $('#txtValorParcelaND').val(valor);
  $('#txtCodigoDeBarrasParcelaND').val(codigoDeBarras);
  $('#txtObservacoesParcelaND').val(observacoes);

  $( "#txtDescricaoParcelaND" ).focus();
})// Evento de click tabelaParcelasND

//#endregion

//#region INCLUSÃO DE DESPESA E DESPESA FIXA---------------------------------------------------------------------------

//Evento do botão que salva a despesa no banco de dados
$('#btnSalvarDespesaND').click(function() {
  //Faz a validação dos campos obrigatórios
  let descricao = $('#txtDescricaoND').val().trim();
  let vencimento = $('#txtVencimentoND').val();
  let valor = $('#txtValorND').val();
  if(descricao.length < 3){
    //Exibe mensagem
    Toast.fire({
      icon: 'error',
      title: "Informe uma descrição."
    })
    $('#txtDescricaoND').focus();
    return;
  }

  if($('#selCategoriaND')[0].checkValidity() == false){
    //Exibe mensagem
    Toast.fire({
      icon: 'error',
      title: "Selecione uma categoria."
    })
    $('#selCategoriaND').focus();
    return;
  }

  //Verifica se irá fazer a inclusão de uma despesa com parcelas ou uma despesa fixa
  let chkDespesaFixa = document.getElementById('chkDespesaFixaND');

  if(chkDespesaFixa.checked) { //Inclusão de Despesa Fixa
    //Faz a validação do campo Vencimento
    if($('#txtVencimentoND')[0].checkValidity() == false){
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: "Selecione uma data de vencimento."
      })
      $('#txtVencimentoND').focus();
      return;
    }
    //Faz a validação do campo Valor
    if(valor == ""){
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: "Informe um valor válido."
      })
      $('#txtValorND').focus();
      return;
    }
    IncluirDespesaFixa()

  } else { //Inclusão de Despesa com Parcela
    //Faz a validação da tabela de parcelas
    if($('#tabelaParcelasND tr').length <= 1){
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: "É necessário incluir pelo menos uma parcela para continuar."
      })
      $('#collapseCriarAlterarParcelaND').collapse("show");
      $( "#txtDescricaoParcelaND" ).focus();
      return;
    }
    IncluirDespesa()
    LimparCamposIncluirDespesa(true);
  }
})//Evento Click btnSalvarDespesaND

//Salvar nova despesa (Validado)
/*
* Para salvar uma nova despesa irei utilizar 2 arrays, o primeiro irá armazenar os dados da despesa como nome, vencimento, etc.. 
* O segundo array irá armazenar os dados das parcelas, que serão geradas dinamicamente na tabela 'tabelaParcelas'
*/
function IncluirDespesa(){
  let url = $('#idURL').val();
  let requisicao = "incluirDespesa";
  let userID = $('#userID').val(); // ID do usuário logado
  let descricao = $('#txtDescricaoND').val();
  let categoria = $("#selCategoriaND").val();
  //Cria um array com os dados principais da Despesa
  var arrayCabecalhoDespesa = {url:url,
    userID:userID,
    descricao:descricao,
    categoria:categoria};
  //console.log("Cabeçalho array de fora");
  //console.log(arrayCabecalhoDespesa);

  //Cria o Array de parcelas, obtido através da tabela 'tabelaParcelas' que é montada dinâmicamente ao clicar no botão 'Gerar Parcelas'
  var indices = [];
  //Pega os indices da tabela
  $('#tabelaParcelasND thead tr th').each(function() {
    indices.push($(this).text());
  });
  //console.log("Cabeçalho parcelas");
  //console.log(indices);

  var arrayParcelas = [];
  //Pecorre todas as parcelas e armazena no array
  $('#tabelaParcelasND tbody tr').each(function( index ) {
    var obj = {};
    let valorFormatado;
    //Controle o objeto
    $(this).find('td').each(function( index ) {
      //Alerta de gambiarra: Tive que utilizar o replace, pois quando estava pegando o valor das parcelas, não estava conseguindo remover o espaço
      //do valor formatado, então eu faço uma verificação para identificar se é uma parcela, se for, eu já faço a formatação antes de enviar para o PHP
      if($(this).text().substring(0, 2) == "R$"){
         valorFormatado = $(this).text().replace(/[^0-9,]*/g, '').replace(',', '.');
      }else{
        valorFormatado = $(this).text(); 
      }
      obj[indices[index]] = valorFormatado;
      //alert(valorFormatado);
    });
    //Adiciona no arrray de objetos
    arrayParcelas.push(obj);
  });
  //Mostra dados pegos no console
  console.log("Listagem das parcelas");
  console.log(arrayParcelas);
  
  //Cria o array final que será um array multidimensional, ele irá conter o array com os dados principais da despesa e tambem o array com as parcelas
  var arrayDespesa = [];
  arrayDespesa.push(arrayCabecalhoDespesa);
  arrayDespesa.push(arrayParcelas);
  //console.log("Array final");
  console.log(arrayDespesa);

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
        url : url,
        type : 'post',
        data : {
          requisicao : requisicao,
          arrayDespesa,      
        },
        dataType: 'json',
        beforeSend : function(){
          //alert(requisicao+" \n "+ url );
        }
  })
  .done(function(msg){
      //alert(msg.mensagem);
      if (msg.success == true){
        //Exibe mensagem
        Toast.fire({
          icon: 'success',
          title: msg.mensagem
        })
        LimparCamposIncluirDespesa();
      }else{
        Toast.fire({
          icon: 'error',
          title: msg.mensagem
        })
      }
  })
  .fail(function(jqXHR, textStatus, msg){
      alert("Erro ao cadastrar despesa: "+"\n"+jqXHR.responseText);
      console.log("Erro ao cadastrar despesa: "+"\n"+jqXHR);
      //console.log("Erro no retorno de dados: "+textStatus+"\n"+msg+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//IncluirDespesa

//Incluir Despesa Fixa
/*
* Despesas fixas não geram parcelas no momento da criação, as parcelas são criadas no momento em que o usuário abre a lista de despesas
* e o sistema identifica que existem despesas fixas não informadas na tabela de despesas.
*/
function IncluirDespesaFixa(){
  let url = $('#idURL').val();
  let requisicao = "incluirDespesaFixa";
  let userID = $('#userID').val(); // ID do usuário logado
  let descricao = $('#txtDescricaoND').val();
  let vencimento = $('#txtVencimentoND').val();
  let valor = $('#txtValorND').val();
  let categoria = $("#selCategoriaND").val();

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
        url : url,
        type : 'post',
        data : {
          requisicao : requisicao,
          userID : userID,
          descricao : descricao,
          vencimento : vencimento,
          valor : valor,
          categoria : categoria,      
        },
        dataType: 'json',
        beforeSend : function(){
          //alert(requisicao+" \n "+ url );
        }
  })
  .done(function(msg){
      //alert(msg.mensagem);
      if (msg.success == true){
        Toast.fire({
          icon: 'success',
          title: msg.mensagem
        })
        LimparCamposIncluirDespesa();
      }else{
        Toast.fire({
          icon: 'error',
          title: msg.mensagem
        })
      }
  })
  .fail(function(jqXHR, textStatus, msg){
      alert("Erro ao cadastrar despesa: "+"\n"+jqXHR.responseText);
      console.log("Erro ao cadastrar despesa: "+"\n"+jqXHR.responseText);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//IncluirDespesaFixa

//Faz a limpeza de todos os campos da tela
function LimparCamposIncluirDespesa(limparSomenteCamposIncluirParcelas = false){
  //Campos de inclusão/Alteração de parcelas
  $('#txtDescricaoParcelaND').val("");
  // $('#selCategoriaParcelaND').val() = "";
  $('#txtNumeroParcelaND').val("");
  $('#txtVencimentoParcelaND').val("");
  $('#txtValorParcelaND').val("");
  $('#txtCodigoDeBarrasParcelaND').val("");
  $('#txtObservacoesParcelaND').val("");

  //Se o parâmetro estiver preenchido executa a limpeza só até aqui
  if(limparSomenteCamposIncluirParcelas == true){
    return;
  }

  //Campos do cabeçalho da despesa
  // $('#chkDespesaFixaND').prop( "checked", false );  não vou limpar esse campo porque pode ser possivel que o usuário esteja fazendo o cadastro só de despesas fixas
  $('#txtDescricaoND').val("");
  // $('#selCategoriaND').val() = "";
  $('#txtVencimentoND').val("");
  $('#txtValorND').val("");
  $('#txtParcelasND').val("1");
  
  //Limpa a tabela de parcelas
  $("#tabelaParcelasBodyND tr").remove();
  //Esconde os campos de inclusão/Alteração de parcelas
  $('#collapseCriarAlterarParcelaND').collapse("hide");

}//LimparCamposIncluirDespesa

//#endregion

//*********************************************************************************************************************
//*******************************************   LISTAR DESPESAS   *****************************************************                  
//*********************************************************************************************************************
//#region LISTAR DESPESAS E FUNÇÕES EXECUTADAS NA TELA DE DESPESAS-----------------------------------------------------

//Faz uma consulta no banco de dados e retorna todas as despesas que possuem parcelas na dta selecionada
function ListarDespesasMensal(){
  //Pega os dados dos campos
  let url = $('#idURL').val();
  let requisicao = "listarDespesasMensal";
  let userID = $('#userID').val(); // ID do usuário logado
  let dataReferencia = $('#txtDataReferenciaDP').val(); // 2121-02
  let totalDespesasPendentes = 0;
  let totalDespesasQuitadas = 0;

  //Valida os dados
  if(dataReferencia == null || dataReferencia == ""){
    Toast.fire({
      icon: 'error',
      title: "A data de referência não pode ser vazia!"
    })
    $('#txtDataReferenciaDP').focus();
    return;
  }

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
      url : url,
      type : 'post',
      data : {
      requisicao : requisicao,
      userID : userID,
      dataReferencia : dataReferencia,      
    },
      dataType: 'json',
      beforeSend : function(){
        //alert(varFuncao+" \n "+ url+" \n "+ elemento +" \n "+ status );
      }
  })
  .done(function(msg){
    if(msg.success == true){
      //Limpa a tabela de Despesas
      $("#tabelaDespesasBodyDP tr").remove();

      let arrayGraficoDespesas = [['Despesa', 'Valor']];
      let valorDespesa;
      let contador;
      //Faz a iteração no array de retorno para inserir linha a linha na tabela de Despesas
      for(var k in msg[0]) {
        //console.log(k, msg[0][k]["descricao"]);

        //Chama a a função que irá inserir as linhas de despesa na tabela, uma a uma
        InserirLinhaTabelaDespesas(msg[0][k]);
        //Somo os valores pendentes e os valores quitados recebidos na consulta para mostrar no rodapé da tabela de despesas
        if(msg[0][k]["quitado"] == "SIM"){
          totalDespesasQuitadas += parseFloat(msg[0][k]["valorquitado"]);
          valorDespesa = parseFloat(msg[0][k]["valorquitado"]);
        }else{
          totalDespesasPendentes += parseFloat(msg[0][k]["valorpendente"]);
          valorDespesa = parseFloat(msg[0][k]["valorpendente"]);
        }
        //Cria um array com os dados da parcela para montar o gráfico de despesas
        arrayGraficoDespesas[parseFloat(k) + 1] = [msg[0][k]["descricao"], valorDespesa]; 
      }
      GraficoDespesaMensal(arrayGraficoDespesas);
      //Exibe os totais no rodapé da tabela de despesas
      $("#idTotalPendente").text(ConverterValorParaRealBrasileiro(totalDespesasPendentes,true));
      $("#idTotalQuitado").text(ConverterValorParaRealBrasileiro(totalDespesasQuitadas,true));
      $("#totalDespesasMensalDP").text(ConverterValorParaRealBrasileiro(totalDespesasPendentes + totalDespesasQuitadas,true));
    }else{
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
    }
    console.log(msg);
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro ao listar Despesas: "+"\n"+jqXHR.responseText);
    console.log("Erro ao listar Despesas: "+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
  
}//ListarDespesasMensal

//Inclui uma linha na tabela de Despesas com os dados recebidos por parâmetro
function InserirLinhaTabelaDespesas(arrayDados){
  let tabelaDeDespesas = document.getElementById("tabelaDespesasBodyDP");
  let novaLinha = tabelaDeDespesas.insertRow(-1);
  let novaCelula;
  //Captura os valores do array
  let id = arrayDados["id"];
  let descricao = arrayDados["descricao"];
  let vencimento = arrayDados["vencimento"];
  let valorPendente = arrayDados["valorpendente"];
  let valorQuitado = arrayDados["valorquitado"];
  let quitado = arrayDados["quitado"];
  let quitacao = arrayDados["quitacao"];
  let quantidaDeParcelas = arrayDados["quantidadeparcelas"];
  let valor;
  let acoes;

  //Formata as datas e valores para o padrão brasileiro
  valorPendente = parseFloat(valorPendente);
  valorQuitado = parseFloat(valorQuitado);
  vencimento = FormatarDataPadraoBrasileiro(vencimento);

  if(quitado == 'SIM'){
    valor = valorQuitado;
  }else{
    valor = valorPendente;
  }

  valor = ConverterValorParaRealBrasileiro(valor,true);

  //Insere os valores na tabela
  novaCelula = novaLinha.insertCell(0); //Coluna ID
  novaCelula.innerHTML = id;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(1);//Coluna Descrição
  if(quantidaDeParcelas > 1){
    novaCelula.innerHTML = '<nobr>'+descricao+'<small class="text-muted"> ('+quantidaDeParcelas+'X)</small></nobr>';
  }else{
    novaCelula.innerHTML = '<nobr>'+descricao+'</nobr>';
  }
  if(quitado == 'SIM'){
    novaCelula.className = "texto-riscado";
  }

  novaCelula = novaLinha.insertCell(2);//Coluna Vencimento
  novaCelula.innerHTML = vencimento;
  novaCelula.className = "text-muted";
  if(quitado != 'SIM'){
    let dataVencimento = new Date(arrayDados["vencimento"]);
    let dataAtual = new Date(DataAtual());
    if(+dataVencimento === +dataAtual){
      novaCelula.className = "text-warning";
    }else if(dataVencimento < dataAtual){
      novaCelula.className = "text-danger";
    }
  } 
  
  novaCelula = novaLinha.insertCell(3);//Coluna Valor
  if(quitado == 'SIM'){
    novaCelula.innerHTML = '<strong>'+valor+'</strong>';
  }else{
    novaCelula.innerHTML = valor;
    novaCelula.className = "text-muted";
  }
  
  novaCelula = novaLinha.insertCell(4);//Coluna Quitado
  novaCelula.innerHTML = quitado;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(5);//Coluna Quantidade Parcelas
  novaCelula.innerHTML = quantidaDeParcelas;
  novaCelula.className = "hidden";
  
  novaCelula = novaLinha.insertCell(6);//Coluna Ações
  if(quitado == 'SIM'){
    acoes = '<nobr>';
    acoes += '<button class="btn btn-info btn-sm" disabled><i class="fas fa-pen"></i></button>';
    acoes += '<a href="" class="btn btn-secondary btn-sm" role="button" data-toggle="modal" data-target="#modal-estornar-despesa" data-id="'+id+'" data-descricao="'+descricao+'" data-qtdeparcelas="'+quantidaDeParcelas+'" data-valorpendente="'+valorPendente+'" data-valorquitado="'+valorQuitado+'" data-vencimento="'+vencimento+'" data-quitacao="'+quitacao+'"><strong class="ml-1 mr-1">E</strong></a>';
    acoes += '</nobr>';
  }else{
    acoes = '<nobr>';
    acoes += '<a href="" class="btn btn-info btn-sm"  role="button" data-toggle="modal" data-target="#modal-editar-despesa" data-id="'+id+'" data-vencimento="'+vencimento+'"><i class="fas fa-pen"></i></a>';
    acoes += '<a href="" class="btn btn-danger btn-sm" role="button" data-toggle="modal" data-target="#modal-quitar-despesa" data-id="'+id+'" data-qtdeparcelas="'+quantidaDeParcelas+'" data-valorpendente="'+valorPendente+'" data-vencimento="'+vencimento+'"><i class="fas fa-dollar-sign ml-1 mr-1"></i></a>';
    acoes += '</nobr>';
  }
  novaCelula.innerHTML = acoes;
  novaCelula.className = "text-center";

  //Exibe mensagem
  // Toast.fire({
  //   icon: 'info',
  //   title: "Tabela de despesas atualizada."
  // })
}//InserirLinhaTabelaDespesa

//Carrega o Modal Quitar Despesa
$('#modal-quitar-despesa').on('show.bs.modal', function (event) {
  let button = $(event.relatedTarget) // Button that triggered the modal
  let id = button.data('id') // Extract info from data-* attributes
  let qtdeParcelas = button.data('qtdeparcelas')
  let valorPendente = button.data('valorpendente')
  valorPendente = ConverterValorParaRealBrasileiro(valorPendente, false);
  let vencimento = button.data('vencimento')
  let modal = $(this)
  modal.find('#txtIdModalQuitarDespesaDP').val(id) // Passa o id salvo no botão para o campo id do modal
  modal.find('#txtQtdeParcelasModalQuitarDespesaDP').val(qtdeParcelas)
  modal.find('#txtVencimentoModalQuitarDespesaDP').val(vencimento)
  //Quando houver mais de 1 parcela na mesma despesa, informo ao usuário que não será possível editar o valor de quitação, pois todas as parcelas serão quitadas
  if(qtdeParcelas > 1){
    let message = "<strong>Atenção</strong>, a despesa selecionada possui <strong>"+qtdeParcelas+"</strong> parcelas com o vencimento na mesma data, portanto elas foram agrupadas e o valor de quitação não pode ser alterado. Caso seja necessário alterar o valor de quitação, clique em editar despesa e altere o valor conforme desejar."
    $('#alert_placeholder').append('<div id="alertdiv" class="alert alert-info"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>')
    modal.find('#txtValorQuitadoModalQuitarDespesaDP').val(valorPendente).attr('readonly', true);
  }else{
    modal.find('#txtValorQuitadoModalQuitarDespesaDP').val(valorPendente).attr('readonly', false);
    $('#alert_placeholder').remove();
  }
  modal.find('#txtDataQuitacaoModalQuitarDespesaDP').val(DataAtual());
})//Carrega o Modal Quitar Despesa

//Quitar Despesa
$("#formModalQuitarDespesaDP").on("submit", function (event) { 
  event.preventDefault();

  let url = $('#idURL').val();
  let requisicao = "quitarDespesa";
  let userID = $('#userID').val(); // ID do usuário logado
  let idDespesa = $('#txtIdModalQuitarDespesaDP').val();
  let qtdeParcelas = $("#txtQtdeParcelasModalQuitarDespesaDP").val();
  let vencimento = $("#txtVencimentoModalQuitarDespesaDP").val();
  let quitacao = $("#txtDataQuitacaoModalQuitarDespesaDP").val();
  let valorQuitado = $('#txtValorQuitadoModalQuitarDespesaDP').val();

  valorQuitado = ConverterRealParaFloat(valorQuitado);
  vencimento = FormataDataPadraoAmericano(vencimento);

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
    url : url,
    type : 'post',
    data : {
      requisicao : requisicao,
      userID : userID,  
      idDespesa : idDespesa,
      qtdeParcelas : qtdeParcelas,
      vencimento : vencimento,   
      quitacao : quitacao,  
      valorQuitado : valorQuitado,
    },
    dataType: 'json',
    beforeSend : function(){
      //alert(vencimento);
    }
  })
  .done(function(msg){
    //alert(msg.mensagem);
    if (msg.success == true){
      //Fecha o modal
      $('#modal-quitar-despesa').modal('hide');

      ListarDespesasMensal();

      //Exibe mensagem
      Toast.fire({
        icon: 'success',
        title: msg.mensagem
      })
     
    }else{
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
      console.log(msg.mensagem);
    }
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro ao quitar despesa: "+"\n"+jqXHR.responseText);
    console.log("Erro ao quitar despesa: "+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados

  //Utilizo esse return false, porque evita do formulário ser submetido, dessa forma a página não é carregada
  return false;
}); //FIM da função Quitar Despesa

//Carrega o Modal Estornar Despesa
$('#modal-estornar-despesa').on('show.bs.modal', function (event) {
  //Coleta os dados informados no botão que chama o modal data-* attributes
  let button = $(event.relatedTarget); // Button that triggered the modal
  let id = button.data('id'); // Extract info from data-* attributes
  let qtdeParcelas = button.data('qtdeparcelas');
  let vencimento = button.data('vencimento');
  let valorPendente = button.data('valorpendente');

  let descricao = button.data('descricao');
  let quitacao = button.data('quitacao');
  let valorQuitado = button.data('valorquitado');
  //Formata os valores coletados
  valorPendente = ConverterValorParaRealBrasileiro(valorPendente, false);
  quitacao = FormataDataBancoDeDadosParaInput(quitacao);
  valorQuitado = ConverterValorParaRealBrasileiro(valorQuitado, false);
  //Preenche os dados coletados no modal
  let modal = $(this);
  modal.find('#txtIdModalEstornarDespesaDP').val(id);
  modal.find('#txtQtdeParcelasModalEstornarDespesaDP').val(qtdeParcelas);
  modal.find('#txtVencimentoModalEstornarDespesaDP').val(vencimento);
  modal.find('#txtValorPendenteModalEstornarDespesaDP').val(valorPendente);

  modal.find('#txtDescricaoModalEstornarDespesaDP').val(descricao);
  modal.find('#txtQuitacaoModalEstornarDespesaDP').val(quitacao);
  modal.find('#txtQuitadoModalEstornarDespesaDP').val(valorQuitado);
})//Carrega o Modal Estornar Despesa

//Estornar Despesa
$("#formModalEstornarDespesaDP").on("submit", function (event) { 
  event.preventDefault();

  let url = $('#idURL').val();
  let requisicao = "estornarDespesa";
  let userID = $('#userID').val(); // ID do usuário logado
  let idDespesa = $('#txtIdModalEstornarDespesaDP').val();
  let qtdeParcelas = $("#txtQtdeParcelasModalEstornarDespesaDP").val();
  let vencimento = $("#txtVencimentoModalEstornarDespesaDP").val();

  vencimento = FormataDataPadraoAmericano(vencimento);

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
    url : url,
    type : 'post',
    data : {
      requisicao : requisicao,
      userID : userID,  
      idDespesa : idDespesa,
      qtdeParcelas : qtdeParcelas,
      vencimento : vencimento,   
    },
    dataType: 'json',
    beforeSend : function(){
      //alert(vencimento);
    }
  })
  .done(function(msg){
    //alert(msg.mensagem);
    if (msg.success == true){
      //Fecha o modal
      $('#modal-estornar-despesa').modal('hide');

      ListarDespesasMensal();

      //Exibe mensagem
      Toast.fire({
        icon: 'success',
        title: msg.mensagem
      })
     
    }else{
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
      console.log(msg.mensagem);
    }
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro ao estornar despesa: "+"\n"+jqXHR.responseText);
    console.log("Erro ao estornar despesa: "+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados

  //Utilizo esse return false, porque evita do formulário ser submetido, dessa forma a página não é carregada
  return false;
}); //FIM da função Estornar Despesa

//Salva a data de referência quando alterada
$("#txtDataReferenciaDP").blur(function(){
  //Captura os valores dos campos
  let url = $('#idURL').val();
  let userID = $('#userID').val(); // ID do usuário logado
  let requisicao = 'atualizarDataReferencia';
  let dataReferencia = $('#txtDataReferenciaDP').val();//yyyy-MM

  dataReferencia = dataReferencia+'-01';//yyyy-MM-DD

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
    url : url,
    type : 'post',
    data : {
    requisicao : requisicao,
    userID : userID,
    dataReferencia : dataReferencia,
  },
    dataType: 'json',
    beforeSend : function(){
      //alert(requisicao + url );
      //console.log(data);
    }
  })
  .done(function(msg){
    //alert(msg.mensagem);
    if (msg.success == true){
      //Exibe mensagem
    }else{
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
    }
    console.log(msg);
  })
  .fail(function(jqXHR, textStatus, msg){
    alert('Erro ao atualizar data de referência: '+jqXHR.responseText);
    console.log('Erro ao atualizar data de referência: '+jqXHR.responseText);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
});//FIM Salva a data de referência quando alterada

function PreencherDataReferencia(){

  //Captura os valores dos campos
  let url = $('#idURL').val();
  let userID = $('#userID').val(); // ID do usuário logado
  let requisicao = 'buscarDataReferencia';

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
    url : url,
    type : 'post',
    data : {
    requisicao : requisicao,
    userID : userID,
  },
    dataType: 'json',
    beforeSend : function(){
      //alert(requisicao + url );
      //console.log(data);
    }
  })
  .done(function(msg){
    console.log(msg);
    if (msg.success == true){
      let dataFormatada;
      dataFormatada = msg[0][0].data_referencia.substring(0,7);//Formato a data de YYYY-mm-dd para YYYY-mm
      $('#txtDataReferenciaDP').val(dataFormatada);
      ListarDespesasMensal();
    }else{
      $('#txtDataReferenciaDP').val(FormataDataParaInputMonth(DataAtual()));
    }
  })
  .fail(function(jqXHR, textStatus, msg){
    alert('Erro ao atualizar data de referência: '+jqXHR.responseText);
    console.log('Erro ao atualizar data de referência: '+jqXHR.responseText);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}

//#endregion

//#region GRÁFICOS DE DESPESA------------------------------------------------------------------------------------------

function GraficoDespesaMensal(arrayGraficoDespesas){
  google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartDespesas);

        function drawChartDespesas() {

          var data = google.visualization.arrayToDataTable(arrayGraficoDespesas);

          //Formata os valores
          var formatCurrency = new google.visualization.NumberFormat({
            pattern: '$###,###,###.00'
          });
          formatCurrency.format(data, 1);

          var options = {
            title: 'Despesas Mensal',
            legend: { position: 'bottom', alignment: 'midlle' },
            is3D:true,
            height:400,
            vAxis: {format:'$###,###,###.00'}, // Money format
          };

          var chart = new google.visualization.PieChart(document.getElementById('grafico-despesas'));

          chart.draw(data, options);
        }

  $(window).resize(function(){
    drawChartDespesas();
  });
}

//#endregion

//#region LISTAR DESPESAS FIXAS----------------------------------------------------------------------------------------

//Faz uma consulta no banco de dados e retorna todas as despesas que possuem parcelas na dta selecionada
function ListarDespesasFixasSemParcela(){
  //Pega os dados dos campos
  let url = $('#idURL').val();
  let requisicao = "listarDespesasFixasSemParcela";
  let userID = $('#userID').val(); // ID do usuário logado
  let dataReferencia = $('#txtDataReferenciaDP').val(); // 2121-02


  //Valida os dados
  if(dataReferencia == null || dataReferencia == ""){
    Toast.fire({
      icon: 'error',
      title: "A data de referência não pode ser vazia!"
    })
    $('#txtDataReferenciaDP').focus();
    return;
  }

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
      url : url,
      type : 'post',
      data : {
      requisicao : requisicao,
      userID : userID,
      dataReferencia : dataReferencia,      
    },
      dataType: 'json',
      beforeSend : function(){
        //alert(varFuncao+" \n "+ url+" \n "+ elemento +" \n "+ status );
      }
  })
  .done(function(msg){
    if(msg.success == true){
      console.log(msg);

      let modalDespesasFixas = $("#modal-despesas-fixas");
      //Limpa a tabela de Despesas
      $("#tabelaDespesasFixasBodyDP tr").remove();
      //Fecha tooltipe descritivo
      $('.tooltip').remove();

      if(typeof msg[0] == 'undefined'){
        ListarDespesasMensal()
        let message = "Nenhuma despesa fixa para ser incluída no mês atual."
        console.log(message);
        Toast.fire({
          icon: 'success',
          title: "Lista de Despesas atualizada com sucesso!"
        })
        //$('#alertModalDespesasFixasDP').append('<div id="alertdiv" class="alert alert-info"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>')
        //modalDespesasFixas.modal('toggle');
        return;
      }

      //Faz a iteração no array de retorno para inserir linha a linha na tabela de Despesas Fixas
      for(var k in msg[0]) {
        //console.log(k, msg[0][k]["descricao"]);
        //return;
        //Chama a a função que irá inserir as linhas de despesa na tabela, uma a uma
        InserirLinhaTabelaDespesasFixas(msg[0][k]);
      }
      modalDespesasFixas.modal('toggle');

    }else{
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
    }
    console.log(msg);
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro ao listar Despesas: "+"\n"+jqXHR.responseText);
    console.log("Erro ao listar Despesas: "+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//ListarDespesasFixasSemParcela

//Inclui uma linha na tabela de Despesas com os dados recebidos por parâmetro
function InserirLinhaTabelaDespesasFixas(arrayDados){
  let tabelaDeDespesas = document.getElementById("tabelaDespesasFixasBodyDP");
  let novaLinha = tabelaDeDespesas.insertRow(-1);
  let novaCelula;
  //Captura os valores do array
  let id = arrayDados["id"];
  let descricao = arrayDados["descricao"];
  let vencimento = arrayDados["vencimento_despesa_fixa"];
  let valor = arrayDados["valor_despesa_fixa"];

  let acoes;

  //Formata as datas e valores para o padrão brasileiro
  valor = parseFloat(valor);
  valor = ConverterValorParaRealBrasileiro(valor,true);
  vencimento = FormatarDataPadraoBrasileiro(vencimento);
  
  //Insere os valores na tabela
  novaCelula = novaLinha.insertCell(0); //Coluna ID
  novaCelula.innerHTML = id;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(1);//Coluna Descrição
  novaCelula.innerHTML = '<nobr>'+descricao+'</nobr>';

  novaCelula = novaLinha.insertCell(2);//Coluna Valor
  novaCelula.innerHTML = valor;

  novaCelula = novaLinha.insertCell(3);//Coluna Vencimento
  novaCelula.innerHTML = vencimento;
  novaCelula.className = "hidden";
  
  novaCelula = novaLinha.insertCell(4);//Coluna Ações
  acoes = '<div class="form-check">';
  acoes += '<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>';
  acoes += '<label class="form-check-label" for="flexCheckChecked">';
  acoes += '';
  acoes += '</label>';
  acoes += '</div>';
  novaCelula.innerHTML = acoes;
  novaCelula.className = "text-center";

  //Exibe mensagem
  // Toast.fire({
  //   icon: 'info',
  //   title: "Tabela de despesas atualizada."
  // })
}//InserirLinhaTabelaDespesasFixas

//#endregion

$("#formModalDespesasFixasDP").on("submit", function (event) { 
  event.preventDefault();

  IncluirParcelasDespesasFixas();
  //Utilizo esse return false, porque evita do formulário ser submetido, dessa forma a página não é carregada
  return false;
}); //FIM da função utilizada para incluir parcelas de despesas fixas


function IncluirParcelasDespesasFixas(){
  let url = $('#idURL').val();
  let requisicao = "incluirParcelasDespesasFixas";
  let userID = $('#userID').val(); // ID do usuário logado

  //Cria o Array de parcelas, obtido através da tabela 'tabelaParcelas' que é montada dinâmicamente ao clicar no botão 'Gerar Parcelas'
  var indices = [];
  //Pega os indices da tabela
  $('#tabelaDespesasFixasDP thead tr th').each(function() {
    indices.push($(this).text());
  });
  //console.log("Cabeçalho parcelas");
  //console.log(indices);

  var arrayParcelas = [];
  //Pecorre todas as parcelas e armazena no array
  $('#tabelaDespesasFixasDP tbody tr').each(function( index ) {
    var obj = {};
    let valorFormatado;
    
    //Verifico se o checkbox está marcado, se estiver incluo a despesa no array de despesas
    if($(this).find('input').prop("checked") == true){
      //Controle o objeto
      $(this).find('td').each(function( index ) {
        //Alerta de gambiarra: Tive que utilizar o replace, pois quando estava pegando o valor das parcelas, não estava conseguindo remover o espaço
        //do valor formatado, então eu faço uma verificação para identificar se é uma parcela, se for, eu já faço a formatação antes de enviar para o PHP
        if($(this).text().substring(0, 2) == "R$"){
          valorFormatado = $(this).text().replace(/[^0-9,]*/g, '').replace(',', '.');
        }else{
          valorFormatado = $(this).text(); 
        }
        obj[indices[index]] = valorFormatado;
        //alert(valorFormatado);
      });
    
      //Adiciona no arrray de objetos
      arrayParcelas.push(obj);
    }
  });
  //Mostra dados pegos no console
  //console.log("Listagem das parcelas");
  //console.log(arrayParcelas);
  
  //Cria o array final que será um array multidimensional, ele irá conter o array com os dados principais da despesa e tambem o array com as parcelas
  var arrayDespesa = [];
  arrayDespesa.push(arrayParcelas);
  //console.log("Array final");
  console.log(arrayDespesa);

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
        url : url,
        type : 'post',
        data : {
          requisicao : requisicao,
          userID : userID,
          arrayDespesa,      
        },
        dataType: 'json',
        beforeSend : function(){
          //alert(requisicao+" \n "+ url );
        }
  })
  .done(function(msg){
      //alert(msg.mensagem);
      if (msg.success == true){
        //Exibe mensagem
        Toast.fire({
          icon: 'success',
          title: msg.mensagem
        })
        ListarDespesasMensal()
      }else{
        Toast.fire({
          icon: 'error',
          title: msg.mensagem
        })
      }
  })
  .fail(function(jqXHR, textStatus, msg){
      alert("Erro ao incluir despesas fixas: "+"\n"+jqXHR.responseText);
      console.log("Erro ao incluir despesas fixas: "+"\n"+jqXHR);
      //console.log("Erro no retorno de dados: "+textStatus+"\n"+msg+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//IncluirParcelasDespesasFixas

//*********************************************************************************************************************
//*******************************************   EDITAR DESPESAS   *****************************************************                  
//*********************************************************************************************************************
//#region CARREGA OS DADOS DA DESPESA E ALIMENTA A TABELA DE PARCELAS--------------------------------------------------

//Carrega o Modal Estornar Despesa
//Bruno aqui não tem modal nenhum apenas aproveitei a função, trocar essa função aqui por um evento de click
$('#modal-editar-despesa').on('show.bs.modal', function (event) {
  //Coleta os dados informados no botão que chama o modal data-* attributes
  let button = $(event.relatedTarget); // Button that triggered the modal
  let id = button.data('id'); // Extract info from data-* attributes
  let vencimento = button.data('vencimento');

  //Formata os valores coletados

  //Armazena os dados coletados em uma sessionStorage (armazenado no navegador)
  id = JSON.stringify(id);
  sessionStorage.setItem('idDespesa', id ); //('chave', valor)
  vencimento = JSON.stringify(vencimento);
  sessionStorage.setItem('vencimento', vencimento );
  /*Para recuperar esse valores basta:
  *var dadosArquivados = JSON.parse(sessionStorage.getItem('chave'));
  *link de referencia: https://pt.stackoverflow.com/questions/75557/passando-valores-js-para-outra-pagina-html
  *Exemplo de uso
  *let dadosArquivados = JSON.parse(sessionStorage.getItem('vencimento'));
  *alert(dadosArquivados);
  */

  //Redireciona para a página de Editar Despesas
  window.location.replace($("#urlEditarDespesa").val());
  return;
})//Carrega o Modal Estornar Despesa

//Faz uma consulta no banco de dados e retorna todas os dados da despesa à ser editada
function PreencherCamposEditarDespesa(){ 
  //Insere os dados da sessionStorage nos campos
  $('#txtDespesaID').val(JSON.parse(sessionStorage.getItem('idDespesa')));
  $('#txtDataVencimentoDespesa').val(JSON.parse(sessionStorage.getItem('vencimento')));

  //Pega os dados dos campos
  let url = $('#idURL').val();
  let requisicao = "listarDadosDespesaPendentePorCodigo";
  let userID = $('#userID').val(); // ID do usuário logado
  //Pega os dados da sessionStorage
  let despesaID = JSON.parse(sessionStorage.getItem('idDespesa'));
  let dataReferencia = JSON.parse(sessionStorage.getItem('vencimento'));

  dataReferencia = FormataDataPadraoAmericano(dataReferencia);

  //Deixa a data de vencimento padronizada na inclusão de novas parcelas
  $("#txtVencimentoParcelaED").val(dataReferencia);

  //Valida os dados
  if(despesaID == null || despesaID == ""){
    Toast.fire({
      icon: 'error',
      title: "Não foi encontrado o ID da despesa a ser atualizada!"
    })
    return;
  }

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
      url : url,
      type : 'post',
      data : {
      requisicao : requisicao,
      userID : userID,
      despesaID : despesaID,
      dataReferencia : dataReferencia,      
    },
      dataType: 'json',
      beforeSend : function(){
        //alert(requisicao+" \n "+ url+" \n "+ dataReferencia +" \n "+ despesaID );
      }
  })
  .done(function(msg){
    if(msg.success == true){
      /*O retorno dessa requisição é um array contendo 2 arrays dentro. 
      *O primeiro array[0] contém os dados do cabeçalho da despesa, os dados que estão na tabela fn_despesas
      *O segundo array[1] contém as parcelas dessa despesa, os dados que estão na tabela fn_despesas_parcelas
      */

      let despesaFixa;
      if(msg[0][0][0]["fixo"] == "SIM"){
        despesaFixa = true
        $('#chkDespesaFixaED').prop( "checked", true );
      }else{
        despesaFixa = false
      }

      //Preenche os dados do cabeçalho da despesa 
      $("#txtDescricaoED").val(msg[0][0][0]["descricao"]);
      $("#selCategoriaED").val(msg[0][0][0]["categorias_id"]);
      if(despesaFixa == true){
        $("#txtVencimentoED").val(msg[0][0][0]["vencimento_despesa_fixa"].substring(0,10));
        $("#txtValorED").val(msg[0][0][0]["valor_despesa_fixa"]);
      }else{
        $("#agrupamentoCampoCategoriaED").addClass("hidden");
        $("#agrupamentoCamposVencimentoValorED").addClass("hidden");
      }

      //Limpa a tabela de parcelas
      $("#tabelaParcelasBodyED tr").remove();
      //Faz a iteração no array de retorno para inserir linha a linha na tabela de parcelas
      for(var k in msg[0][1]) {
        //console.log(k, msg[0][1][k]["descricao"]);
        InserirLinhaTabelaParcelas(msg[0][1][k]);
      }
    }else{
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
    }
    console.log(msg);
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro ao listar os dados da Despesa: "+"\n"+jqXHR.responseText);
    console.log("Erro ao listar os dados da Despesa: "+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//PreencherCamposEditarDespesa

//Inclui uma linha na tabela de Despesas com os dados recebidos por parâmetro
function InserirLinhaTabelaParcelas(arrayDados){
  let tabelaDeParcelas = document.getElementById("tabelaParcelasBodyED");
  let novaLinha = tabelaDeParcelas.insertRow(-1);
  let novaCelula;
  //Captura os valores do array
  let id = arrayDados["id"];
  let descricao = arrayDados["descricao"];
  let valorPendente = arrayDados["valorpendente"];
  let vencimento = arrayDados["vencimento"];
  let valorQuitado = arrayDados["valorquitado"];
  let quitado = arrayDados["quitado"];
  let quitacao = arrayDados["quitacao"];
  let codigoDeBarras = arrayDados["codigo_de_barras"];
  let observacoes = arrayDados["observacoes"];
  let categoria = arrayDados["fn_categorias_id"];
  let quantidaDeParcelas = arrayDados["quantidadeparcelas"];
  let valor;
  let acoes;

  //Formata as datas e valores para o padrão brasileiro
  valorPendente = parseFloat(valorPendente);
  valorQuitado = parseFloat(valorQuitado);
  vencimento = FormatarDataPadraoBrasileiro(vencimento);

  if(quitado == 'SIM'){
    valor = valorQuitado;
  }else{
    valor = valorPendente;
  }

  valor = ConverterValorParaRealBrasileiro(valor,true);

  //Insere os valores na tabela
  novaCelula = novaLinha.insertCell(0); //Coluna Parcela
  novaCelula.innerHTML = id;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(1);//Coluna Descrição
  novaCelula.innerHTML = '<nobr>'+descricao+'</nobr>';

  novaCelula = novaLinha.insertCell(2);//Coluna Vencimento
  novaCelula.innerHTML = vencimento;
  
  novaCelula = novaLinha.insertCell(3);//Coluna Valor
  novaCelula.innerHTML = valor;

  novaCelula = novaLinha.insertCell(4);//Coluna Categoria
  novaCelula.innerHTML = categoria;
  novaCelula.className = "hidden";
  
  novaCelula = novaLinha.insertCell(5);//Coluna Código de Barras
  novaCelula.innerHTML = codigoDeBarras;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(6);//Coluna Observações
  novaCelula.innerHTML = observacoes;
  novaCelula.className = "hidden";
  
  novaCelula = novaLinha.insertCell(7);//Coluna Ações
  acoes = '<nobr>';
  acoes += '<a href="" class="btn btn-danger btn-sm" role="button" data-toggle="modal" data-target="#modal-deletar-parcela-despesa" data-id="'+id+'" data-descricao="'+descricao+'"  data-vencimento="'+vencimento+'"><i class="fas fa-trash ml-1 mr-1"></i></a>';
  acoes += '</nobr>';
  novaCelula.innerHTML = acoes;
  novaCelula.className = "text-center";

  //Exibe mensagem
  // Toast.fire({
  //   icon: 'info',
  //   title: "Tabela de despesas atualizada."
  // })
}//InserirLinhaTabelaDespesa

//#endregion

//#region INCLUSÃO E ALTERAÇÃO DE PARCELAS-----------------------------------------------------------------------------

//Função utilizada para incluir ou editar parcelas individualmente (validado)
//$('#btnIncluirAlterarParcelaND').click(function() { //Não utilizo mais essa forma, pois a submissão do formulário já faz a validação dos campos
$("#formParcelaDespesaED").on("submit", function (event) { 
  event.preventDefault();
  //Verifica se o usuário está incluindo ou alterando uma parcela
  let numeroParcela = $('#txtNumeroParcelaED').val();

  if(numeroParcela == ""){ // Modo de Inclusão
    IncluirParcelaED();
    LimparCamposEditarDespesa(true);
    $('#collapseCriarAlterarParcelaED').collapse("hide");
  }else{ // Modo de Alteração
    AlterarParcelaED(numeroParcela);
    LimparCamposEditarDespesa(true);
    $('#collapseCriarAlterarParcelaED').collapse("hide");
  }
  //Utilizo esse return false, porque evita do formulário ser submetido, dessa forma a página não é carregada
  return false;
}); //FIM da função utilizada para incluir ou editar parcelas individualmente

//Faz a inclusão da nova parcela direto no banco de dados
function IncluirParcelaED(){
  //Captura os valores dos campos
  let url = $('#idURL').val();
  let userID = $('#userID').val(); // ID do usuário logado
  let requisicao = "incluirParcelaDespesa";

  let idDespesa = $("#txtDespesaID").val();
  let descricao = document.getElementById("txtDescricaoParcelaED").value;
  let vencimento = $("#txtVencimentoParcelaED").val();
  let valor = ConverterRealParaFloat($("#txtValorParcelaED").val());
  let codigoCategoria = parseInt($("#selCategoriaParcelaED").val());
  let codigoDeBarras = document.getElementById("txtCodigoDeBarrasParcelaED").value;
  let observacoes = document.getElementById("txtObservacoesParcelaED").value;

  let objParcelaDespesa = {Parcela:descricao,
    Vencimento:vencimento,
    Valor:valor,
    Categoria:codigoCategoria,
    CodigoDeBarras:codigoDeBarras,
    Observacoes:observacoes};
  /** ALERTA DE GAMBIARRA!
   * Ainda não sei o motivo. mas para conseguir utilizar o array no PHP tive que utilizar esse push aqui no JS (estudar mais sobre isso)
   */
  let arrayParcelaDespesa = [];
  arrayParcelaDespesa.push(objParcelaDespesa);
  
  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
    url : url,
    type : 'post',
    data : {
      requisicao : requisicao,
      userID : userID,  
      idDespesa : idDespesa,
      arrayParcelaDespesa,    
    },
    dataType: 'json',
    beforeSend : function(){
      //alert(requisicao+" \n "+ url );
      //console.log(data);
    }
  })
  .done(function(msg){
    //alert(msg.mensagem);
    if (msg.success == true){
      PreencherCamposEditarDespesa();
      //Exibe mensagem
      Toast.fire({
        icon: 'success',
        title: msg.mensagem
      })
    }else{
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
    }
    console.log(msg);
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro ao incluir parcela: "+"\n"+jqXHR.responseText);
    console.log("Erro ao incluir parcela: "+"\n"+jqXHR.responseText);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//IncluirParcelaED

//Faz a inclusão da nova parcela direto no banco de dados
function AlterarParcelaED(){
  //Captura os valores dos campos
  let url = $('#idURL').val();
  let userID = $('#userID').val(); // ID do usuário logado
  let requisicao = "alterarParcelaDespesa";

  let idDespesa = $("#txtDespesaID").val();
  let idParcela = $("#txtNumeroParcelaED").val();
  let descricao = document.getElementById("txtDescricaoParcelaED").value;
  let vencimento = $("#txtVencimentoParcelaED").val();
  let valor = ConverterRealParaFloat($("#txtValorParcelaED").val());
  let codigoCategoria = parseInt($("#selCategoriaParcelaED").val());
  let codigoDeBarras = document.getElementById("txtCodigoDeBarrasParcelaED").value;
  let observacoes = document.getElementById("txtObservacoesParcelaED").value;

  let objParcelaDespesa = {Parcela:descricao,
    Vencimento:vencimento,
    Valor:valor,
    Categoria:codigoCategoria,
    CodigoDeBarras:codigoDeBarras,
    Observacoes:observacoes};
  /** ALERTA DE GAMBIARRA!
   * Ainda não sei o motivo. mas para conseguir utilizar o array no PHP tive que utilizar esse push aqui no JS (estudar mais sobre isso)
   */
  let arrayParcelaDespesa = [];
  arrayParcelaDespesa.push(objParcelaDespesa);
  
  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
    url : url,
    type : 'post',
    data : {
      requisicao : requisicao,
      userID : userID,  
      idDespesa : idDespesa,
      idParcela : idParcela,
      arrayParcelaDespesa,    
    },
    dataType: 'json',
    beforeSend : function(){
      //alert(requisicao+" \n "+ url );
      //console.log(data);
    }
  })
  .done(function(msg){
    //alert(msg.mensagem);
    if (msg.success == true){
      PreencherCamposEditarDespesa();
      //Exibe mensagem
      Toast.fire({
        icon: 'success',
        title: msg.mensagem
      })
    }else{
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
    }
    console.log(msg);
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro ao alterar parcela: "+"\n"+jqXHR.responseText);
    console.log("Erro ao alterar parcela: "+"\n"+jqXHR.responseText);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//AlterarParcelaED

//Evento executado ao clicar no botão de cancelar inclus]ao ou alteração de parcelas
$('#btnCancelarInclusaoParcelaED').click(function() { 
  LimparCamposEditarDespesa(true);
  $('#collapseCriarAlterarParcelaED').collapse("hide");
});//Evento Inclusão/Alteração parcelas

//Ao clicar nas linhas da tabela de despesa os dados da parcela sobem para serem editados
$("#tabelaParcelasED tbody").on('click', 'tr', function () {
  $('#collapseCriarAlterarParcelaED').collapse("show");

  let parcela = $('td:eq(0)', this).text().trim();
  let descricao = $('td:eq(1)', this).text().trim();
  let vencimento = $('td:eq(2)', this).text().trim();
  let valor = $('td:eq(3)', this).text().trim();
  let categoria = $('td:eq(4)', this).text().trim();
  let codigoDeBarras = $('td:eq(5)', this).text().trim();
  let observacoes = $('td:eq(6)', this).text().trim();
  //Converte a data de vencimento para o padrão americano, o input do tipo date só recebe datas no formato YYYY-MM-DD
  vencimento = FormataDataPadraoAmericano(vencimento);
  //Remove o R$ se tiver
  valor = valor.replace("R$","");
  valor = valor.trim();
  valor = ConverterValorParaRealBrasileiro(valor, false);

  $('#txtDescricaoParcelaED').val(descricao);
  $('#selCategoriaParcelaED').val(categoria);
  $('#txtNumeroParcelaED').val(parcela);
  $('#txtVencimentoParcelaED').val(vencimento);
  $('#txtValorParcelaED').val(valor);
  $('#txtCodigoDeBarrasParcelaED').val(codigoDeBarras);
  $('#txtObservacoesParcelaED').val(observacoes);

  $( "#txtDescricaoParcelaED" ).focus();
})// Evento de click tabelaParcelasED

//Faz a limpeza de todos os campos da tela
function LimparCamposEditarDespesa(limparSomenteCamposIncluirParcelas = false){
  //Campos de inclusão/Alteração de parcelas
  $('#txtDescricaoParcelaED').val("");
  // $('#selCategoriaParcelaED').val() = "";
  $('#txtNumeroParcelaED').val("");
  $('#txtValorParcelaED').val("");
  $('#txtCodigoDeBarrasParcelaED').val("");
  $('#txtObservacoesParcelaED').val("");

  //Se o parâmetro estiver preenchido executa a limpeza só até aqui
  if(limparSomenteCamposIncluirParcelas == true){
    return;
  }

  //Campos do cabeçalho da despesa
  // $('#chkDespesaFixaED').prop( "checked", false );  não vou limpar esse campo porque pode ser possivel que o usuário esteja fazendo o cadastro só de despesas fixas
  $('#txtDescricaoED').val("");
  // $('#selCategoriaND').val() = "";
  $('#txtVencimentoED').val("");
  $('#txtValorED').val("");
  $('#txtParcelasED').val("1");
  
  //Limpa a tabela de parcelas
  $("#tabelaParcelasBodyED tr").remove();
  //Esconde os campos de inclusão/Alteração de parcelas
  
  $('#collapseCriarAlterarParcelaED').collapse("hide");

}//LimparCamposEditarDespesa

//#endregion

//#region EXCLUSÃO DE PARCELAS-----------------------------------------------------------------------------------------

//Carrega o Modal Deletar Despesa
$('#modal-deletar-parcela-despesa').on('show.bs.modal', function (event) {
  //Coleta os dados informados no botão que chama o modal data-* attributes
  let button = $(event.relatedTarget); // Button that triggered the modal
  let id = button.data('id'); // Extract info from data-* attributes
  let vencimento = button.data('vencimento');
  let descricao = button.data('descricao');

  let modal = $(this)
  modal.find('#txtModalExcluirNumeroParcelaED').val(id) // Passa o id salvo no botão para o campo id do modal
  modal.find('#modalDeletarParcelaDespesaTitleED').text("Deseja realmente exluir a parcela: "+descricao)

  //ALERTA DE GAMBIARRA!
  /**O botão de exluir parcela fica dentro da tabela de parcelas, porem essa tabela tem um evento de click nas linhas (tr) 
   * Que ao serem clicadas, sobe os dados para o painel de edição, porém quando eu clico no botão de excluir parcela
   * não quero que esse evento aconteça, mas o momento não sei como impedir isso, então criei a gambiarra abaixo para limpar os campos
   * e fechar o painel de edição.
   */
   LimparCamposEditarDespesa(true);
   $('#collapseCriarAlterarParcelaED').collapse('hide');
  

  return;
})//Carrega o Modal Deletar Despesa

//Faz a exclusão de parcelas, pega o id da parcela a ser excluida no input oculto do modal excluir e envia na requisição ajax
function DeletarParcelaDespesaED(){
  //Captura os valores dos campos
  let url = $('#idURL').val();
  let userID = $('#userID').val(); // ID do usuário logado
  let requisicao = "excluirParcelaDespesa";

  let idDespesa = $("#txtDespesaID").val();
  let idParcela = $("#txtModalExcluirNumeroParcelaED").val();
  
  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
    url : url,
    type : 'post',
    data : {
      requisicao : requisicao,
      userID : userID,  
      idDespesa : idDespesa,
      idParcela : idParcela,  
    },
    dataType: 'json',
    beforeSend : function(){
      //alert(requisicao+" \n "+ url );
      //console.log(data);
    }
  })
  .done(function(msg){
    //alert(msg.mensagem);
    if (msg.success == true){
      //Fecha o modal
      $('#modal-deletar-parcela-despesa').modal('toggle');

      PreencherCamposEditarDespesa();

      //Exibe mensagem
      Toast.fire({
        icon: 'success',
        title: msg.mensagem
      })
    }else{
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: msg.mensagem
      })
    }
    console.log(msg);
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro ao excluir parcela: "+"\n"+jqXHR.responseText);
    console.log("Erro ao excluir parcela: "+"\n"+jqXHR.responseText);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//DeletarParcelaDespesaED

//#endregion

//#region ALTERAÇÃO DE DESPESA E DESPESA FIXA (CABEÇALHO)--------------------------------------------------------------

//Evento do botão que salva a despesa no banco de dados
$('#btnSalvarDespesaED').click(function() {
  //Faz a validação dos campos obrigatórios
  let descricao = $('#txtDescricaoED').val().trim();

  if(descricao.length < 3){
    //Exibe mensagem
    Toast.fire({
      icon: 'error',
      title: "Informe uma descrição."
    })
    $('#txtDescricaoED').focus();
    return;
  }

  //Verifica se irá fazer a inclusão de uma despesa com parcelas ou uma despesa fixa
  let chkDespesaFixa = document.getElementById('chkDespesaFixaED');

  if(chkDespesaFixa.checked) { //Inclusão de Despesa Fixa
    //Faz a validação do campo Categoria
    if($('#selCategoriaED')[0].checkValidity() == false){
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: "Selecione uma categoria."
      })
      $('#selCategoriaED').focus();
      return;
    }
    //Faz a validação do campo Vencimento
    if($('#txtVencimentoED')[0].checkValidity() == false){
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: "Selecione uma data de vencimento."
      })
      $('#txtVencimentoED').focus();
      return;
    }
    //Faz a validação do campo Valor
    if($('#txtValorED') == ""){
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: "Informe um valor válido."
      })
      $('#txtValorED').focus();
      return;
    }
    AlterarDespesaFixa()

  } else { //Inclusão de Despesa com Parcela
    //Faz a validação da tabela de parcelas
    if($('#tabelaParcelasED tr').length <= 1){
      //Exibe mensagem
      Toast.fire({
        icon: 'error',
        title: "É necessário incluir pelo menos uma parcela para continuar."
      })
      $('#collapseCriarAlterarParcelaED').collapse("show");
      $( "#txtDescricaoParcelaED" ).focus();
      return;
    }
    AlterarDespesa()
  }
})//Evento Click btnSalvarDespesaED

//Salvar nova despesa (Validado)
/*
* Para salvar uma nova despesa irei utilizar 2 arrays, o primeiro irá armazenar os dados da despesa como nome, vencimento, etc.. 
* O segundo array irá armazenar os dados das parcelas, que serão geradas dinamicamente na tabela 'tabelaParcelas'
*/
function AlterarDespesa(){
  let url = $('#idURL').val();
  let requisicao = "alterarDespesa";
  let userID = $('#userID').val(); // ID do usuário logado

  let idDespesa = $("#txtDespesaID").val();
  let descricao = $("#txtDescricaoED").val();

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
        url : url,
        type : 'post',
        data : {
          requisicao : requisicao,
          userID : userID,
          idDespesa : idDespesa,
          descricao : descricao,      
        },
        dataType: 'json',
        beforeSend : function(){
          //alert(requisicao+" \n "+ url );
        }
  })
  .done(function(msg){
      //alert(msg.mensagem);
      if (msg.success == true){
        //Exibe mensagem
        Toast.fire({
          icon: 'success',
          title: msg.mensagem
        })
      }else{
        Toast.fire({
          icon: 'error',
          title: msg.mensagem
        })
        console.log(msg.mensagem);
      }
  })
  .fail(function(jqXHR, textStatus, msg){
      alert("Erro ao alterar despesa: "+"\n"+jqXHR.responseText);
      console.log("Erro ao alterar despesa: "+"\n"+jqXHR);
      //console.log("Erro no retorno de dados: "+textStatus+"\n"+msg+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//AlterarDespesa

//Alterar Despesa Fixa
/*
* Despesas fixas não geram parcelas no momento da criação, as parcelas são criadas no momento em que o usuário abre a lista de despesas
* e o sistema identifica que existem despesas fixas não informadas na tabela de despesas.
*/
function AlterarDespesaFixa(){
  let url = $('#idURL').val();
  let requisicao = "alterarDespesaFixa";
  let userID = $('#userID').val(); // ID do usuário logado

  let idDespesa = $("#txtDespesaID").val();
  let descricao = $("#txtDescricaoED").val();
  let vencimento = $('#txtVencimentoED').val();
  let valor = $('#txtValorED').val();
  let categoria = $("#selCategoriaED").val();

  //vencimento = FormataDataPadraoAmericano(vencimento);
  valor = ConverterRealParaFloat(valor);

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
        url : url,
        type : 'post',
        data : {
          requisicao : requisicao,
          idDespesa : idDespesa,
          userID : userID,
          descricao : descricao,
          vencimento : vencimento,
          valor : valor,
          categoria : categoria,      
        },
        dataType: 'json',
        beforeSend : function(){
          //alert(vencimento);
        }
  })
  .done(function(msg){
      //alert(msg.mensagem);
      if (msg.success == true){
        Toast.fire({
          icon: 'success',
          title: msg.mensagem
        })
      }else{
        Toast.fire({
          icon: 'error',
          title: msg.mensagem
        })
        console.log(msg.mensagem)
      }
  })
  .fail(function(jqXHR, textStatus, msg){
      alert("Erro ao editar despesa: "+"\n"+jqXHR.responseText);
      console.log("Erro ao editar despesa: "+"\n"+jqXHR.responseText);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//AlterarDespesaFixa

//#endregion