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

//-------------------------------------------------------------------------------------------------------------------------------

//Função utilizada para gerar as parcelas ao criar uma despesa (Validado - Falta revisar os comentários)
$('#btnGerarParcelas').click(function() {
    
  let valorTotal = parseFloat($("#NDvalor").val().replace('.',''));
  let valorPago = parseFloat($("#valorPago").val()).toFixed(2);
  let parcelas = parseInt($("#NDparcelas").val());
  //let melhorDia = parseInt($("#melhorDia").val());
  let melhorDia = $("#NDvencimento").val().replace(/-/g, ",");

  if ((valorTotal) && (valorPago) && (parcelas)) {
    // ENCONTRA O VALOR LÍQUIDO
    let valorLiquido = parseFloat(valorTotal - valorPago);
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
      table += '<tr><td>' + par  + '</td>';
      table += '<td>' + primeiraParcela.toLocaleDateString() + '</td>';
      table += '<td>' + valorParcela.toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' }) + '</td></tr>';
      par++;
      primeiraParcela.setMonth(primeiraParcela.getMonth() + 1); // AUMENTA UM MÊS      
    }
    $('#tabelaParcelas tbody').html(table);
  }
}); //FIM da função utilizada para gerar as parcelas ao criar uma despesa

//-------------------------------------------------------------------------------------------------------------------------------

//Função utilizada para incluir ou editar parcelas individualmente
$('#btnIncluirParcela').click(function() {
  //Captura os valores dos campos
  let tabelaDeParcelas = document.getElementById("tabelaParcelas");
  let numeroDaParcela = document.getElementById("NDIncluirNumeroParcela").value;
  let vencimento = $("#NDIncluirVencimento").val().replace(/-/g, ",");
  let valor = parseFloat($("#NDIncluirValor").val().replace('.',''));
  let novaLinha = tabelaDeParcelas.insertRow(-1);
  let novaCelula;
  let vencimentoFormatado;
  // if (geral % 2 == 0)
  //     cortabela = "#FFFF00";
  // else
  //     cortabela = "#FF7F00";
  // alert(numeroDaParcela);
  // alert(vencimento);
  // alert(valor);

  //Formata as datas e valores para o padrão brasileiro
  vencimentoFormatado = new Date(vencimento);
  vencimentoFormatado = vencimentoFormatado.toLocaleDateString();
  valor = valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' })

  if (numeroDaParcela == ""){
    numeroDaParcela = $('#tabelaParcelas tr').length - 1;
    //Insere os valores na tabela
    novaCelula = novaLinha.insertCell(0);
    // novaCelula.style.backgroundColor = cortabela;
    novaCelula.innerHTML = numeroDaParcela;

    novaCelula = novaLinha.insertCell(1);
    // novaCelula.style.backgroundColor = cortabela;
    novaCelula.innerHTML = vencimentoFormatado;

    novaCelula = novaLinha.insertCell(2);
    // novaCelula.style.backgroundColor = cortabela;
    novaCelula.innerHTML = valor;
    
    //Bruno Verificar se compensa inserir um botão para excluir as parcelas ou então subir e realizar a exclusão
    // novaCelula = novaLinha.insertCell(3);
    // novaCelula.style.backgroundColor = cortabela;
    // novaCelula.innerHTML = '<input type="button" value="X" onclick="deleteRow(this)"/>';
  }



}); //FIM da função utilizada para incluir ou editar parcelas individualmente

//-------------------------------------------------------------------------------------------------------------------------------

//Salvar nova despesa (Validado)
/*
* Para salvar uma nova despesa irei utilizar 2 arrays, o primeiro irá armazenar os dados da despesa como nome, vencimento, etc.. 
* O segundo array irá armazenar os dados das parcelas, que serão geradas dinamicamente na tabela 'tabelaParcelas'
*/
$('#incluirDespesaBTN').click(function() {

  let url = $('#idURL').val();
  let varFuncao = "incluirDespesa";
  let userID = $('#userID').val(); // ID do usuário logado
  let descricao = $('#NDdescricao').val();
  //Cria um array com os dados principais da Despesa
  var arrayCabecalhoDespesa = {url:url,
    userID:userID,
    descricao:descricao};
  //console.log(arrayCabecalhoDespesa);

  //Cria o Array de parcelas, obtido através da tabela 'tabelaParcelas' que é montada dinâmicamente ao clicar no botão 'Gerar Parcelas'
  var indices = [];
  //Pega os indices da tabela
  $('#tabelaParcelas thead tr th').each(function() {
    indices.push($(this).text());
  });
  var arrayParcelas = [];
  //Pecorre todas as parcelas e armazena no array
  $('#tabelaParcelas tbody tr').each(function( index ) {
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
  //console.log(arrayParcelas);
  
  //Cria o array final que será um array multidimensional, ele irá conter o array com os dados principais da despesa e tambem o array com as parcelas
  var arrayDespesa = [];
  arrayDespesa.push(arrayCabecalhoDespesa);
  arrayDespesa.push(arrayParcelas);
  console.log(arrayDespesa);

  //Requisição Ajax para enviar os dados para o banco de dados
    $.ajax({
         url : url,
         type : 'post',
         data : {
          varFuncao : varFuncao,
          arrayDespesa,      
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
    });
  //Fim da requisição Ajax para enviar os dados para o banco de dados
  
})