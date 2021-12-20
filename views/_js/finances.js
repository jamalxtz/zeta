//Função utilizada para gerar as parcelas ao criar uma despesa (Validado - Falta revisar os comentários)
$('#btnGerarParcelasND').click(function() {
    
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
}); //FIM da função utilizada para gerar as parcelas ao criar uma despesa

//-------------------------------------------------------------------------------------------------------------------------------

//Função utilizada para incluir ou editar parcelas individualmente (validado)
$('#btnIncluirParcelaND').click(function() {
  //Captura os valores dos campos
  //let tabelaDeParcelas = document.getElementById("tabelaParcelasND");
  let tabelaDeParcelas = document.getElementById("tabelaParcelasBodyND");
  let numeroDaParcela = document.getElementById("txtNumeroParcelaND").value;
  let vencimento = $("#txtVencimentoParcelaND").val().replace(/-/g, ",");
  let valor = parseFloat($("#txtValorParcelaND").val().replace('.',''));
  let descricao = document.getElementById("txtDescricaoParcelaND").value;
  let codigoCategoria = parseInt($("#selCategoriaParcelaND").val());
  let codigoDeBarras = document.getElementById("txtCodigoDeBarrasParcelaND").value;
  let observacoes = document.getElementById("txtObservacoesParcelaND").value;
  let novaLinha = tabelaDeParcelas.insertRow(-1);
  let novaCelula;
  let vencimentoFormatado;

  //Formata as datas e valores para o padrão brasileiro
  vencimentoFormatado = new Date(vencimento);
  vencimentoFormatado = vencimentoFormatado.toLocaleDateString();
  valor = valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' })

  if (numeroDaParcela == ""){
    numeroDaParcela = $('#tabelaParcelasND tr').length - 1;
    //Insere os valores na tabela
    novaCelula = novaLinha.insertCell(0);
    novaCelula.innerHTML = numeroDaParcela;

    novaCelula = novaLinha.insertCell(1);
    novaCelula.innerHTML = descricao;

    novaCelula = novaLinha.insertCell(2);
    novaCelula.innerHTML = vencimentoFormatado;

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
  }



}); //FIM da função utilizada para incluir ou editar parcelas individualmente

//-------------------------------------------------------------------------------------------------------------------------------

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

//Função que executa os eventos da tabela
$('#tabelaParcelasBodyND').click(function() {

  $('#collapseCriarAlterarParcelaND').collapse("show");


  //alert($thatRow.closest('tr').find('td').eq(1).text());

  // $("#txtDescricaoParcelaND").val($thatRow.closest('tr').find('td').eq(1).text());

})//Evento Click chkDespesaFixaND

//-------------------------------------------------------------------------------------------------------------------------------

//Evento do botão que salva a despesa no banco de dados
$('#btnSalvarDespesaND').click(function() {
  let chkDespesaFixa = document.getElementById('chkDespesaFixaND');

  if(chkDespesaFixa.checked) {
    IncluirDespesaFixa()
  } else {
    IncluirDespesa()
  }
})//Evento Click btnSalvarDespesaND

//-------------------------------------------------------------------------------------------------------------------------------

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
      alert(msg.mensagem);
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
      alert(msg.mensagem);
  })
  .fail(function(jqXHR, textStatus, msg){
      alert("Erro ao cadastrar despesa: "+"\n"+jqXHR.responseText);
      console.log("Erro ao cadastrar despesa: "+"\n"+jqXHR);
  });//Fim da requisição Ajax para enviar os dados para o banco de dados
}//IncluirDespesaFixa

























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