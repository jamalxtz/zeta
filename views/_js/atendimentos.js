//**********************************************************************************************************************



//função que EXECUTA o atendimento
function buscarAtendimento(url){
  //essa variavel pega o id do usuario que esta logado para fazer as operações com o banco de dados
  var idAtendimento = 1;

    $.ajax({
         url : url,
         type : 'post',
         data : {
              varFuncao : 'mostrar',      
         },
         dataType: 'json',
         beforeSend : function(){
            alert(idAtendimento+" \n "+ url);
         }
    })
    .done(function(msg){
        alert(JSON.stringify(msg));
        document.location.reload(true);

    })

    .fail(function(jqXHR, textStatus, msg){
        alert("Erro no retorno de dados: "+textStatus+"\n"+msg);
        console.log("Erro no retorno de dados: "+textStatus+"\n"+msg+"\n"+jqXHR);
    });



} //FIM da função que executa o atendimento

//**********************************************************************************************************************