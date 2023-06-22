
function ConverteParaData(data) {
    var dataArray = data.split('/');
    var novaData = new Date(dataArray[2], dataArray[1], dataArray[0]);

    return novaData;
};

/* Função Validar */
function validar() {

    var data1 = document.getElementById("dataInicioAtendimento");

    var data2 = document.getElementById("dataFimAtendimento");

    var dataInicial = ConverteParaData(data1.value);
    var dataFinal = ConverteParaData(data2.value);

    //alert($("#dataFimReparo").val());
    if (dataInicial > dataFinal) {
        alert("ERRO! Data Final menor que a data Inicial");

        // Deixa o input com o focus
        data2.focus();
        // retorna a função e não olha as outras linhas
        return false;
    } else {
        return true;
    }
}



$(document).ready(function () {
    //#################################################################################
    //=================================  TABELAS ======================================
    //#################################################################################
    $('.tables_datatable').dataTable({

        "oLanguage": {
            "sLengthMenu": "Mostrar _MENU_ registros por página",
            "sZeroRecords": "Nenhum registro encontrado",
            "sInfo": "Mostrando _START_ / _END_ de _TOTAL_ registro(s)",
            "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
            "sInfoFiltered": "(filtrado de _MAX_ registros)",
            "sSearch": "Pesquisar: ",
            "oPaginate": {
                "sFirst": "Início",
                "sPrevious": "Anterior",
                "sNext": "Próximo",
                "sLast": "Último"
            }
        }
    });

    //#################################################################################
    //===============  DATAPICKER E CONTROLE DE SELECAO DE DATAS  =====================
    //#################################################################################

    // ##############  Formulario dados atendimento ##################################3#
    $("#dataInicioAtendimento").on("change", function () {
        // Setter
        dti = $("#dataInicioAtendimento").val();
        var dataArray = dti.split('/');
        $("#dataFimAtendimento").datepicker("option", "minDate", new Date(dataArray[2], dataArray[1] - 1, dataArray[0]));
    });


    $("#statusAtendimento").on("change", function () {
        var valor = $(this).val(); // aqui vc pega cada valor selecionado com o this
        var dt = $("#dataTemp").val();
        dti = $("#dataInicioAtendimento").val();
        var dataArray = dti.split('/');
        if (valor == 3) {

            //$("#formDadosAtendimento").append('<div id="div_dtFimAtd" class="form-group datas col-md-3"><label for="dataFimAtendimento">Data do Fim do atendimento</label><input type="text" class="form-control" id="dataFimAtendimento" name="dataFimAtendimento" value="' + dt + '" required placeholder="dd/mm/aaaa"></div>');
            $("#formDadosAtendimento").append('<div class="form-group col-md-3" id="div_dtFimAtd">' +
                '<label for="dataFimAtendimento" class="font-weight-bold">Data do Fim do atendimento</label>' +
                '<div class="input-group">' +
                '<span class="input-group-btn">' +
                '<button tabindex="-1" type="button" class="btn btn-default border">' +
                '<i class="fa fa-calendar"></i>' +
                '</button>' +
                '</span>' +
                '<input type="text" class="form-control datas" id="dataFimAtendimento" name="dataFimAtendimento" required value="' + dt + '" placeholder="dd/mm/aaaa">' +
                '</div>' +
                '</div>');


            $("#dataFimAtendimento").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                selectOtherMonths: true,
                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                minDate: new Date(dataArray[2], dataArray[1] - 1, dataArray[0])

            });
        }
        if (valor == 2 || valor == 1) {
            if ($('#div_dtFimAtd')) {
                $('#div_dtFimAtd').remove();
            }
        }
    });

    // ##############  Formulario novo reparo ##################################3#



    $("#dataInicioReparo").on("change", function () {
        // Setter
        dti = $("#dataInicioReparo").val();
        var dataArray = dti.split('/');
        $("#dataFimReparo").datepicker("option", "minDate", new Date(dataArray[2], dataArray[1] - 1, dataArray[0]));
    });




    //#################################################################################
    //=================================  MODAIS  ======================================
    //#################################################################################

    $('.datas').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],

    });

    $(document).on('click', '.btn_view_detalhes_atendimento', function () {
        var user_id = $(this).attr("id");
        //alert(user_id);
        //Verificar se há valor na variável "user_id" 

        if (user_id !== '') {
            var dados = {
                user_id: user_id
            };
            $.post('../atendimentos/modalDetalhesAtendimento.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                //alert(retorna);
                $("#visual_usuario").html(retorna);
                $('#visualUsuarioModal').modal('show');

            });
        }
    });

    $(document).on('click', '.view_detalhes_reparo', function () {
        var user_id = $(this).attr("id");
        //alert(user_id);
        //Verificar se há valor na variável "user_id" 

        if (user_id !== '') {
            var dados = {
                user_id: user_id
            };
            $.post('../atendimentos/modalDetalhesReparo.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                //alert(retorna);
                $("#visual_detalhes_reparo").html(retorna);
                $('#visualReparoModal').modal('show');

            });
        }
    });

    $(document).on('click', '.view_editar_reparo', function () {
        var user_id = $(this).attr("id");
        var numAtendimento = $("#numAtendimento").val();
        

        //alert(user_id);
        //Verificar se há valor na variável "user_id" 

        if (user_id !== '') {
            var dados = {
                user_id: user_id,
                numAtendimento : numAtendimento
            };
            $.post('../atendimentos/modalEditarReparo.php', dados, function (retorna) {

                $("#visual_edt_reparo").html(retorna);
                $('#editarReparoModal').modal('show');

                // toda logica do controle de datas no modal
                $('#editarReparoModal').on('shown.bs.modal', function (e) {

                    // corrige o erro de q quando carrega o modal com data final, o usuario consegue colocar data final menor q a inicial
                    $(document).ready(function () {
                        dti = $("#dataInicioReparo").val();
                        dtfa = $("#dataTempInicioAtendimento").val();
                        
                        var dataArray = dti.split('/');
                        var dataArray2 = dtfa.split('/');
                        $("#dataFimReparo").datepicker("option", "minDate", new Date(dataArray[2], dataArray[1] - 1, dataArray[0]));
                        // seta a data minima do reparo para a data inicial do atendimento
                        $("#dataInicioReparo").datepicker("option", "minDate", new Date(dataArray2[2], dataArray2[1] - 1, dataArray2[0]))
                    });

                    // corrige o erro de q quando a data inicial eh modificada. adiciona um limite inicial na data final
                    $("#dataInicioReparo").on("change", function () {
                        // Setter
                        dti = $("#dataInicioReparo").val();
                        var dataArray = dti.split('/');
                        $("#dataFimReparo").datepicker("option", "minDate", new Date(dataArray[2], dataArray[1] - 1, dataArray[0]));
                    });

                    // inicializa os datepicker
                    $('.datas').datepicker({
                        dateFormat: 'dd/mm/yy',
                        changeMonth: true,
                        changeYear: true,
                        showOtherMonths: true,
                        selectOtherMonths: true,
                        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
                        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    });

                    // toda logica de quando o select status eh mudado
                    $("#statusReparo").on("change", function () {

                        var valor = $(this).val(); // aqui vc pega cada valor selecionado com o this
                        
                        var dt = $("#dataTempReparo").val();
                        dti = $("#dataInicioReparo").val();
                        var dataArray = dti.split('/');

                        // se select eh igual a concluido
                        if (valor == 3) {
                            // adiciona o input data final no formulario
                            $('#form_edt_reparo').append('<div class="form-group col-md-3" id="div_dtFimRep">' +
                                '<label for="dataFimReparo" class="font-weight-bold">Data do Fim do reparo</label>' +
                                '<div class="input-group">' +
                                '<span class="input-group-btn">' +
                                '<button tabindex="-1" type="button" class="btn btn-default border">' +
                                '<i class="fa fa-calendar"></i>' +
                                '</button>' +
                                '</span>' +
                                '<input type="text" class="form-control datas" id="dataFimReparo" name="dataFimReparo" required value="' + dt + '" placeholder="dd/mm/aaaa">' +
                                '</div>' +
                                '</div>');

                            // inicializa o datepicker do input que acabou de ser criado
                            $("#dataFimReparo").datepicker({
                                dateFormat: 'dd/mm/yy',
                                changeMonth: true,
                                changeYear: true,
                                showOtherMonths: true,
                                selectOtherMonths: true,
                                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
                                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                                minDate: new Date(dataArray[2], dataArray[1] - 1, dataArray[0])

                            });
                        }
                        // se select for diferente de 3, então remove o input de data final
                        if (valor == 2 || valor == 1) {
                            if ($('#div_dtFimRep')) {
                                $('#div_dtFimRep').remove();
                            }
                        }
                    });
                });


            });
        }
    });

    $(document).on('click', '.view_excluir_reparo', function () {
        var user_id = $(this).attr("id");
        //alert(user_id);
        //Verificar se há valor na variável "user_id" 

        if (user_id !== '') {
            var dados = {
                user_id: user_id
            };
            $.post('../atendimentos/modalExcluirReparo.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                //alert(retorna);
                $("#visual_excl_reparo").html(retorna);
                $('#excluirReparoModal').modal('show');

            });
        }
    });

    $(document).on('click', '.btn_view_cadastrar_reparo', function () {

        var user_id = $(this).attr("id");
        
        //alert(user_id);
        //Verificar se há valor na variável "user_id" 

        if (user_id !== '') {
            var dados = {
                user_id: user_id
            };
            $.post('../atendimentos/modalCadastarReparo.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                //alert(retorna);
                $("#visual_cad_reparo").html(retorna);

                $('#cadastrarReparoModal').modal('show');

                // toda logica do controle de datas no modal
                $('#cadastrarReparoModal').on('shown.bs.modal', function (e) {

                    // corrige o erro de q quando carrega o modal com data final, o usuario consegue colocar data final menor q a inicial
                    $(document).ready(function () {
                        dti = $("#dataInicioReparo").val();
                        dtfa = $("#dataTempInicioAtendimento").val();
                        var dataArray = dti.split('/');
                        var dataArray2 = dtfa.split('/');
                        $("#dataFimReparo").datepicker("option", "minDate", new Date(dataArray[2], dataArray[1] - 1, dataArray[0]));
                        // seta a data minima do reparo para a data inicial do atendimento
                        $("#dataInicioReparo").datepicker("option", "minDate", new Date(dataArray2[2], dataArray2[1] - 1, dataArray2[0]));
                    });

                    // corrige o erro de q quando a data inicial eh modificada. adiciona um limite inicial na data final
                    $("#dataInicioReparo").on("change", function () {
                        // Setter
                        dti = $("#dataInicioReparo").val();
                        var dataArray = dti.split('/');
                        $("#dataFimReparo").datepicker("option", "minDate", new Date(dataArray[2], dataArray[1] - 1, dataArray[0]));
                    });

                    // inicializa os datepicker
                    $('.datas').datepicker({
                        dateFormat: 'dd/mm/yy',
                        changeMonth: true,
                        changeYear: true,
                        showOtherMonths: true,
                        selectOtherMonths: true,
                        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
                        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],

                    });

                    // toda logica de quando o select status eh mudado
                    $("#statusReparoCad").on("change", function () {
                        var valor = $(this).val(); // aqui vc pega cada valor selecionado com o this
                        //var dt = $("#dataTempReparo").val();
                        dti = $("#dataInicioReparo").val();
                        
                        var dataArray = dti.split('/');

                        // se select eh igual a concluido
                        if (valor == 3) {
                            // adiciona o input data final no formulario
                            $('#formDadosReparo').append('<div class="form-group col-md-3" id="div_dtFimRepCad">' +
                                '<label for="dataFimReparo" class="font-weight-bold">Data do Fim do reparo</label>' +
                                '<div class="input-group">' +
                                '<span class="input-group-btn">' +
                                '<button tabindex="-1" type="button" class="btn btn-default border">' +
                                '<i class="fa fa-calendar"></i>' +
                                '</button>' +
                                '</span>' +
                                '<input type="text" class="form-control datas" id="dataFimReparo" name="dataFimReparo" required placeholder="dd/mm/aaaa">' +
                                '</div>' +
                                '</div>');

                            // inicializa o datepicker do input que acabou de ser criado
                            $("#dataFimReparo").datepicker({
                                dateFormat: 'dd/mm/yy',
                                changeMonth: true,
                                changeYear: true,
                                showOtherMonths: true,
                                selectOtherMonths: true,
                                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
                                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                                minDate: new Date(dataArray[2], dataArray[1] - 1, dataArray[0])

                            });
                        }
                        // se select for diferente de 3, então remove o input de data final
                        if (valor == 2 || valor == 1) {
                            if ($('#div_dtFimRepCad')) {
                                $('#div_dtFimRepCad').remove();
                            }
                        }
                    });
                });
            });
        }
    });


});

$(document).ready(function () {
    dti = $("#dataInicioAtendimento").val();
    var dataArray = dti.split('/');
    $("#dataFimAtendimento").datepicker("option", "minDate", new Date(dataArray[2], dataArray[1] - 1, dataArray[0]));
});


$(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })


//$("#telefone, #celular").mask("(00) 0000-0000");

