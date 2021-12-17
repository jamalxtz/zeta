$('#colorPickerBackground').on('change', function() {
  //alert(this.value);
  $('#menuExemplo').css('background-color', this.value);
  
  //$('#menuExemplo').css('background-image', 'linear-gradient(to bottom left, '+this.value+', rgba(0,0,0,0)');
});


$('#colorPickerTexto').on('change', function() {
  //alert(this.value);
  $('#menuExemplo').css('color', this.value);
});


function mouseMove(){
  let corHover = $("#colorPickerHover").val();
  //alert(corHover);
  $('#menuExemplo').css('color', corHover);
}

function mouseOut(){
  let corTexto = $("#colorPickerTexto").val();
  //alert(corHover);
  $('#menuExemplo').css('color', corTexto);
}
//************************************************************************************************************************
