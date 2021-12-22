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
  
  let valorTotal = parseFloat($("#txtValorND").val().replace('.',''));
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
      table += '<tr>'
      table += '<td>' + par  + '</td>';//Número da parcela
      table += '<td>' + par  + '</td>';//Descrição
      table += '<td>' + primeiraParcela.toLocaleDateString() + '</td>';//Vencimento
      table += '<td>' + valorParcela.toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' }) + '</td>';//Valor
      table += '<td>' + codCategoria  + '</td>';//Categoria
      table += '<td>' + codigoDeBarras  + '</td>';//Categoria
      table += '<td>' + observacoes  + '</td>';//Categoria
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
    $('#txtVencimentoND').prop('readonly', true);
    $('#txtParcelasND').prop('readonly', true);
    $('#btnGerarParcelasND').prop('disabled', true);
    $('#btnCollapseCriarAlterarParcelaND').prop('disabled', true);
    $('#collapseCriarAlterarParcelaND').collapse("hide");

  } else {//Ao desabilitar o cadastro de despesa fixa.
    $('#txtVencimentoND').prop('readonly', false);
    $('#txtParcelasND').prop('readonly', false);
    $('#btnGerarParcelasND').prop('disabled', false);
    $('#btnCollapseCriarAlterarParcelaND').prop('disabled', false);
  }
})//Evento Click chkDespesaFixaND

//-------------------------------------------------------------------------------------------------------------------------------

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
  let numeroDaParcela = $('#tabelaParcelasND tr').length - 1;
  let vencimento = $("#txtVencimentoParcelaND").val();
  let valor = parseFloat($("#txtValorParcelaND").val().replace('.',''));
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

  novaCelula = novaLinha.insertCell(2);
  novaCelula.innerHTML = vencimento;

  novaCelula = novaLinha.insertCell(3);
  novaCelula.innerHTML = valor;

  novaCelula = novaLinha.insertCell(4);
  novaCelula.innerHTML = codigoCategoria;

  novaCelula = novaLinha.insertCell(5);
  novaCelula.innerHTML = codigoDeBarras;

  novaCelula = novaLinha.insertCell(6);
  novaCelula.innerHTML = observacoes;
  
  //Bruno Verificar se compensa inserir um botão para excluir as parcelas ou então subir e realizar a exclusão
  // novaCelula = novaLinha.insertCell(3);
  // novaCelula.style.backgroundColor = cortabela;
  // novaCelula.innerHTML = '<input type="button" value="X" onclick="deleteRow(this)"/>';
  
  //Limpa o campo de descrição
  $('#tabelaParcelasND tr').val("");
  $.notify("Parcela incluída.", 'info');

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

  $.notify("Parcela alterada.", 'info');
  
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

//-------------------------------------------------------------------------------------------------------------------------------

//Evento do botão que salva a despesa no banco de dados
$('#btnSalvarDespesaND').click(function() {
  //Faz a validação dos campos obrigatórios
  let descricao = $('#txtDescricaoND').val().trim();
  let valor = $('#txtValorND').val();
  if(descricao.length < 3){
    $.notify("Informe uma descrição válida para a despesa.", 'error');
    $('#txtDescricaoND').focus();
    return;
  }

  //Verifica se irá fazer a inclusão de uma despesa com parcelas ou uma despesa fixa
  let chkDespesaFixa = document.getElementById('chkDespesaFixaND');

  if(chkDespesaFixa.checked) { //Inclusão de Despesa Fixa
    //Faz a validação do campo Valor
    if(valor == ""){
      $.notify("Informe um valor válido.", 'error');
      $('#txtValorND').focus();
      return;
    }
    IncluirDespesaFixa()

  } else { //Inclusão de Despesa com Parcela
    //Faz a validação da tabela de parcelas
    if($('#tabelaParcelasND tr').length - 1 <= 0){
      $.notify("É necessário incluir pelo menos uma parcela para continuar.", 'error');
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
        $.notify(msg.mensagem, "success");
        LimparCamposIncluirDespesa();
      }else{
        $.notify(msg.mensagem, "error");
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
        $.notify(msg.mensagem, "success");
        LimparCamposIncluirDespesa();
      }else{
        $.notify(msg.mensagem, "error");
      }
  })
  .fail(function(jqXHR, textStatus, msg){
      alert("Erro ao cadastrar despesa: "+"\n"+jqXHR.responseText);
      console.log("Erro ao cadastrar despesa: "+"\n"+jqXHR);
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

//-------------------------------------------------------------------------------------------------------------------------------

//Recebe a data no formato DD/MM/AAAA e retorna no padrão YYYY-MM-DD
function FormataDataPadraoAmericano(data) {
  var dia  = data.split("/")[0];
  var mes  = data.split("/")[1];
  var ano  = data.split("/")[2];

  return ano + '-' + ("0"+mes).slice(-2) + '-' + ("0"+dia).slice(-2);
  // Utilizo o .slice(-2) para garantir o formato com 2 digitos.
}//FormataDataPadraoAmericano

//Recebe a data no formato YYYY-MM-DD e retorna no padrão DD/MM/AAAA 
function FormatarDataPadraoBrasileiro(data){
  //Troca os traços por vírgulas
  data = data.replace(/-/g, ",");
  //Formata as datas e valores para o padrão brasileiro
  let dataFormatada = new Date(data);
  dataFormatada = dataFormatada.toLocaleDateString();
  return dataFormatada;
}//FormatarDataPadraoBrasileiro

//Recebe o valor no formato 1.222.222,56 e retorna no padrão 1222222.56
function ConverterRealParaFloat(valor){
  if(valor === ""){
      valor =  0;
  }else{
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


















//-----------------------------------------------------------------------------------------------------------------
//Listar Despesas

function ListarDespesas(){
  //Pega os dados dos campos
  let url = $('#idURL').val();
  let varFuncao = "listarDespesas";
  let userID = $('#userID').val(); // ID do usuário logado
  let dataReferencia = $('#dataReferencia').val(); // 2121-02

  //Valida os dados
  if(dataReferencia == null || dataReferencia == ""){
    alert("A data de referência não pode ser vazia!");
    $('#dataReferencia').focus();
    return;
  }

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
      url : url,
      type : 'post',
      data : {
      varFuncao : varFuncao,
      userID : userID,
      dataReferencia : dataReferencia,      
    },
      dataType: 'json',
      beforeSend : function(){
        //alert(varFuncao+" \n "+ url+" \n "+ elemento +" \n "+ status );
      }
  })
  .done(function(msg){
    alert(msg.mensagem);
  })
  .fail(function(jqXHR, textStatus, msg){
    alert("Erro no retorno de dados: "+textStatus+"\n"+msg);
    console.log("Erro no retorno de dados: "+textStatus+"\n"+msg+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
  
}//ListarDespesas

//-----------------------------------------------------------------------------------------------------------------

$('#modal-editar-despesa').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('id') // Extract info from data-* attributes
  var modal = $(this)
  modal.find('#modal-editar-id').val(recipient) // Passa o id salvo no botão para o campo id do modal
  
  let url = $('#idURL').val();
  let varFuncao = "buscarDadosDespesa";
  let id = $('#modal-editar-id').val(); // ID da despesa
  let userID = $('#userID').val(); // ID do usuário logado
  let dataParametro = $('#dataParametro').val(); // Data em que está cadastrada a despesa
  //alert(url);
  //Requisição Ajax para enviar os dados para o banco de dados
    $.ajax({
         url : url,
         type : 'post',
         data : {
              varFuncao : varFuncao,
              id : id, 
              userID : userID,
              dataParametro : dataParametro,         
         },
         dataType: 'json',
         beforeSend : function(){
            //alert(varFuncao+" \n "+ url+" \n "+ elemento +" \n "+ status );
         }
    })
    .done(function(msg){
        console.log(msg.mensagem);
    })
    .fail(function(jqXHR, textStatus, msg){
        alert("Erro no retorno de dados: "+textStatus+"\n"+msg);
        console.log("Erro no retorno de dados: "+textStatus+"\n"+msg+"\n"+jqXHR);
    });
  //Fim da requisição Ajax para enviar os dados para o banco de dados
  
})