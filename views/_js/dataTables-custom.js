
$(document).ready(function() {
	//monta a tabela de acordo com o framework DataTable
    var table = $('#dataTable').DataTable();

    //pega a tabela e as linhas da tabela, usaremos essa variavel para pegar os dados da linha selecionada
    var tabela = document.getElementById("dataTable");
	var linhas = tabela.getElementsByTagName("tr");
    
	//função que seleciona a linha que clicamos na tabela
    $('#dataTable tbody').on( 'click', 'tr', function () {
    	//se a linha ja tiver selecionada ele remove a seleção
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
        	//se nao tiver ele marca ela como selecionada
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

             //pega a linha selecionada e armazena os dados dela para jogar nos campos do atendimento
             var selecionados = tabela.getElementsByClassName("selected");

             var dados = "";
  
			  for(var i = 0; i < selecionados.length; i++){
			  	var selecionado = selecionados[i];
			    selecionado = selecionado.getElementsByTagName("td");

			    
			    document.getElementById("cliente-edit").innerHTML = (selecionado[2].innerHTML);
			    $("#id-edit").val(selecionado[0].innerHTML);
			    document.getElementById("problema-edit").innerHTML = (selecionado[6].innerHTML);
			    $("#solucao-edit").val(selecionado[7].innerHTML);
                $("#situacao-edit").val(selecionado[8].innerHTML);
			   
			    // document.getElementById("solucao-edit").innerHTML = (selecionado[7].innerHTML);

     			
			    //DESCOMENTAR SE FOR DEBUGAR: dados += "ID: " + selecionado[0].innerHTML + " - Nome: " + selecionado[1].innerHTML + " - Idade: " + selecionado[2].innerHTML + "\n";
			  }//fim do for
			  //DESCOMENTAR SE FOR DEBUGAR: alert(dados);

        }//fim do else
    } );//fim da função de click
 	
} );//fim do document ready
