2
/*
Para adicionar codigo dinamicamente com jQuery, pode usar o metodo append.

$("#frmCadastro").append('<div class="row"> ... </div>');
Ou se quiser mudar o conteudo, pode usar o metodo html

$("#frmCadastro").html('<div class="row"> ... </div>');
Para remover o ultimo elemento do form, pode usar remove

$('#frmCadastro').children('div').last().remove();
Se quiser remover um elemento especifico, aconselho usar um Id no div.

$('#id_div').remove();

*/