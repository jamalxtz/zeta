//#region DECLARAÇÃO DE VARIÁVEIS--------------------------------------------------------------------------------------

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

//#endregion

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

//#region FUNÇÕES DE CONVERSÃO-----------------------------------------------------------------------------------------

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




window.onload = function() {
  alert("Carregou a página");

};


//-----------------------------------------------------------------------------------------------------------------

//Faz uma consulta no banco de dados e retorna todas as despesas que possuem parcelas na dta selecionada
function ListarDespesasMensal(){
  //Pega os dados dos campos
  let url = $('#idURL').val();
  let requisicao = "listarDespesasMensal";
  let userID = $('#userID').val(); // ID do usuário logado
  let dataReferencia = $('#dataReferencia').val(); // 2121-02

  //Valida os dados
  if(dataReferencia == null || dataReferencia == ""){
    alert("A data de referência não pode ser vazia!");
    $('#dataReferencia').focus();
    return;
  }

  let mes = "12";
  let ano = "2021" 

  //Requisição Ajax para enviar os dados para o banco de dados
  $.ajax({
      url : url,
      type : 'post',
      data : {
      requisicao : requisicao,
      userID : userID,
      mes : mes,  
      ano : ano,    
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
      //Faz a iteração no array de retorno para inserir linha a linha na tabela de Despesas
      for(var k in msg[0]) {
        //console.log(k, msg[0][k]["descricao"]);
        InserirLinhaTabelaDespesas(msg[0][k]);
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
    alert("Erro ao listar Despesas: "+"\n"+jqXHR.responseText);
    console.log("Erro ao listar Despesas: "+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
  
}//ListarDespesasMensal

//Inclui uma linha na tabela de Despesas com os dados recebidos por parâmetro
function InserirLinhaTabelaDespesas(arrayDados){
  let tabelaDeParcelas = document.getElementById("tabelaDespesasBodyDP");
  let novaLinha = tabelaDeParcelas.insertRow(-1);
  let novaCelula;
  //Captura os valores do array
  let id = arrayDados["id"];
  let descricao = arrayDados["descricao"];
  let valorPendente = arrayDados["valorpendente"];
  let valorQuitado = arrayDados["valorquitado"];
  let quitado = arrayDados["quitado"];
  let quantidaDeParcelas = arrayDados["quantidadeparcelas"];
  let valor;
  let acoes;

  //Formata as datas e valores para o padrão brasileiro
  valorPendente = parseFloat(valorPendente);
  valorPendente = ConverterValorParaRealBrasileiro(valorPendente,true)
  valorQuitado = parseFloat(valorQuitado);
  valorQuitado = ConverterValorParaRealBrasileiro(valorQuitado,true)

  if(quitado == 'SIM'){
    valor = valorQuitado;
  }else{
    valor = valorPendente;
  }

  //Insere os valores na tabela
  novaCelula = novaLinha.insertCell(0); //Coluna ID
  novaCelula.innerHTML = id;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(1);//Coluna Descrição
  if(quantidaDeParcelas > 1){
    novaCelula.innerHTML = descricao+'<nobr><small class="text-muted"> ('+quantidaDeParcelas+'X)</small></nobr>';
  }else{
    novaCelula.innerHTML = descricao;
  }
  
  
  novaCelula = novaLinha.insertCell(2);//Coluna Valor
  if(quitado == 'SIM'){
    novaCelula.innerHTML = '<strong>'+valor+'</strong>';
  }else{
    novaCelula.innerHTML = valor;
    novaCelula.className = "text-muted";
  }
  
  novaCelula = novaLinha.insertCell(3);//Coluna Quitado
  novaCelula.innerHTML = quitado;
  novaCelula.className = "hidden";

  novaCelula = novaLinha.insertCell(4);//Coluna Quantidade Parcelas
  novaCelula.innerHTML = quantidaDeParcelas;
  novaCelula.className = "hidden";
  
  novaCelula = novaLinha.insertCell(5);//Coluna Ações
  if(quitado == 'SIM'){
    acoes = '<nobr>';
    acoes += '<button class="btn btn-info btn-sm" disabled><i class="fas fa-pen"></i></button>';
    acoes += '<a href="" class="btn btn-secondary btn-sm" role="button" data-toggle="modal" id="modal-estornar-despesa" data-id="'+id+'"><strong class="ml-1 mr-1">E</strong></a>';
    acoes += '</nobr>';
  }else{
    acoes = '<nobr>';
    acoes += '<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-editar-despesa" data-id="'+id+'"><i class="fas fa-pen"></i></button>';
    acoes += '<a href="" class="btn btn-danger btn-sm" role="button" data-toggle="modal" data-target="#modal-quitar-despesa" data-id="'+id+'" data-qtdeparcelas="'+quantidaDeParcelas+'" data-valor="'+valorPendente+'"><i class="fas fa-dollar-sign ml-1 mr-1"></i></a>';
    acoes += '</nobr>';
  }
  novaCelula.innerHTML = acoes;
  novaCelula.className = "text-center";

  //Exibe mensagem
  Toast.fire({
    icon: 'info',
    title: "Tabela de despesas atualizada."
  })
}//InserirLinhaTabelaDespesa

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

//Modal Quitar Despesa
$('#modal-quitar-despesa').on('show.bs.modal', function (event) {
  let button = $(event.relatedTarget) // Button that triggered the modal
  let id = button.data('id') // Extract info from data-* attributes
  let qtdeParcelas = button.data('qtdeparcelas')
  let valor = button.data('valor')
  let modal = $(this)
  modal.find('#txtIdModalQuitarDespesaDP').val(id) // Passa o id salvo no botão para o campo id do modal
  modal.find('#txtQtdeParcelasModalQuitarDespesaDP').val(qtdeParcelas)
  //Quando houver mais de 1 parcela na mesma despesa, informo ao usuaário que não será possível editar o valor de quitação, pois todas as parcelas serão quitadas
  if(qtdeParcelas > 1){
    let message = "<strong>Atenção</strong>, a despesa selecionada possui <strong>"+qtdeParcelas+"</strong> parcelas com o vencimento nesse mesmo mês, portanto elas foram agrupadas e o valor de quitação não pode ser alterado. Caso seja necessário alterar o valor de quitação, clique em editar despesa e altere o valor conforme desejar."
    $('#alert_placeholder').append('<div id="alertdiv" class="alert alert-info"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>')
    modal.find('#txtValorQuitadoModalQuitarDespesaDP').val(valor).attr('readonly', true);
  }else{
    modal.find('#txtValorQuitadoModalQuitarDespesaDP').val(valor).attr('readonly', false);
  }
  modal.find('#txtDataQuitacaoModalQuitarDespesaDP').val(DataAtual());
  
})