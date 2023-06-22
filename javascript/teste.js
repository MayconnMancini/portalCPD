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
                    var dataArray = dti.split('/');
                    $("#dataFimReparo").datepicker("option", "minDate", new Date(dataArray[2], dataArray[1] - 1, dataArray[0]));
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
                    alert('capturei o change')
                    //var dt = $("#dataTempReparo").val();
                    dti = $("#dataInicioReparo").val();
                    var dataArray = dti.split('/');

                    // se select eh igual a concluido
                    if (valor == 3) {
                        // adiciona o input data final no formulario
                        $('#formDadosReparo').append('<div class="form-group col-md-3" id="div_dtFimRep">' +
                            '<label for="dataFimReparo">Data do Fim do reparo</label>' +
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
                        if ($('#div_dtFimRep')) {
                            $('#div_dtFimRep').remove();
                        }
                    }
                });
            });
        });
    }
});