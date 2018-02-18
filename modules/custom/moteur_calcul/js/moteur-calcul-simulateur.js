jQuery(document).ready(function ($) {
    $('#moteur_calcul_simulateur_form_submit').on("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (!IsValidSimulateurEcran1($)) {
            $('#block_ecran1 .alert-messages ul').html('');
            $("<li class='has-error'>Les données suivantes sont obligatoires : merci de les saisir avant de valider.</li>").appendTo("#block_ecran1 .alert-messages ul");
            return false;
        } else {
            $('#block_ecran1 .alert-messages ul').html('');
        }
        var age = $('#field_age input').val();
        var salaire = $('#field_salaire_annuel_brut input').val();
        var request = $.ajax({
            url: '/moteur-calcul/simulateur/ecran2/salaire/' + salaire,
            method: "GET",
            dataType: "json"
        });
        request.done(function (data) {
            if (data.hasOwnProperty('status') && data.status === "error") {
                return false;
            }
            if (data.hasOwnProperty('salaireMensuelNet')) {
                $('#salaire_mensuel_net').html(data.salaireMensuelNet);
            }
            if (data.hasOwnProperty('allocationMensuelNetPoleEmploi')) {
                $('#allocation_mensuel_net_pole_emploi').html(data.allocationMensuelNetPoleEmploi);
            }
            if (data.hasOwnProperty('manqueGagnerMensuelNet')) {
                $('#manque_ganger_mensuel_net').html(data.manqueGagnerMensuelNet);
            }
            $('#block_ecran2').removeClass('hidden');
            if ($('#block_ecran3').length) {
                display_ecran3($, age, salaire); // display part 3
            }
        });
        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });

    });

    $('#select-montantMonsuelIndemnise').on("change", function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('#input-montantMonsuelIndemnise').val($(this).val());
        updateMontantMensuelDesCotisations($, true);


    });
    $('#select-duree-montant-mensuel-cotisations').on("change", function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('#input-duree-montant-mensuel-cotisations').val($(this).val());
        updateMontantMensuelDesCotisations($, true);
    });

    $('input[name="age"]').on("keypress", function (event) {
        return isNumberKey(event);

    });
    $('input[name="age"]').keyup(function () {
        numberOnly(this);
    });

    $('input[name="age"]').on("blur", function () {
        if ($(this).val().match(/[^\w\s]/gi) !== null) {
            $(this).val('');
        }
        $(this).val(parseInt($('input[name="age"]').val()));
    });
    $('input[name="age"]').bind("cut copy paste", function (e) {
        e.preventDefault();
    });

    $('input[name="salaire"]').on("keypress", function (event) {
        return isNumberKey(event);
    });
    $('input[name="salaire"]').keyup(function () {
        numberOnly(this);
    });
    $('input[name="salaire"]').on("blur", function () {
        if ($(this).val().match(/[^\w\s]/gi) !== null) {
            $(this).val('');
        }
        $(this).val(parseInt($($('input[name="salaire"]')).val()));
    });
    $('input[name="salaire"]').bind("cut copy paste", function (e) {
        e.preventDefault();
    });

    if (!$('body').hasClass('simulateur-simple')) {
        $('.select-montantMonsuelIndemnise').select2({width: '100%', minimumResultsForSearch: -1});
        $('.select-duree-montant-mensuel-cotisations').select2({width: '100%', minimumResultsForSearch: -1});
    }


});
/**
 *
 * @param {Object} $ jQuery
 * @param {int} age
 * @param {int} salaire
 * @returns void
 */
function display_ecran3($, age, salaire) {
    var request = $.ajax({
        url: '/moteur-calcul/simulateur/ecran3/age/' + parseInt(age) + '/salaire/' + parseInt(salaire),
        method: "GET",
        dataType: "json"
    });
    request.done(function (data) {
        if (data.hasOwnProperty('status') && data.status === "success") {
            $('#block_ecran3 .alert-messages ul').html('');
            $('#block_ecran3 .content').removeClass('hidden');
            initInputMontantMonsuelIndemniseRange($, data.indeminiteMensuelleMin, data.plafontIndeminiteMensuelleMax, data.indeminiteMensuellePas);
            initInputinputDureeCotisationsRange($, data.dureeIndemnisationMin, data.dureeIndemnisationMax, data.dureeIndemnisationDefault);
            // affichage mobile
            initSelectMontantMonsuelIndemniseRange($, data.indeminiteMensuelleMin, data.plafontIndeminiteMensuelleMax, data.indeminiteMensuellePas);
            initSelectDureeCotisationsRange($, data.dureeIndemnisationMin, data.dureeIndemnisationMax, data.dureeIndemnisationDefault);

            setTimeout(function () {
                updateMontantMensuelDesCotisations($);
            }, 1000);

        }

        if (data.hasOwnProperty('status') && data.status === "error") {
            if (data.hasOwnProperty('errors')) {
                $('#block_ecran3 .alert-messages ul').html('');
                $('#block_ecran3 .content').addClass('hidden');
                data.errors.messages.forEach(function (element) {
                    $("<li class='has-error'>" + element + "</li>").appendTo("#block_ecran3 .alert-messages ul");
                });
            }
        }
        $('#block_ecran3').removeClass('hidden');
    });
    request.fail(function (jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}

function updateMontantMensuelDesCotisations($) {
    var age = $('#field_age input').val();
    var salaire = $('#field_salaire_annuel_brut input').val();
    var montantMensuelIndemnise = 0;
    var dureeIndemnisation = 0;

    if ($('#input-montantMonsuelIndemnise').is(":visible") && $('#input-duree-montant-mensuel-cotisations').is(":visible")) {
        // desktop values
        montantMensuelIndemnise = Number($('#input-montantMonsuelIndemnise').val());
        dureeIndemnisation = Number($('#input-duree-montant-mensuel-cotisations').val());
    } else if ($('#select-montantMonsuelIndemnise').is(":visible") && $('#select-duree-montant-mensuel-cotisations').is(":visible")) {
        // mobile values
        montantMensuelIndemnise = Number($('#select-montantMonsuelIndemnise').val());
        dureeIndemnisation = Number($('#select-duree-montant-mensuel-cotisations').val());
    }

    var request = $.ajax({
        url: '/moteur-calcul/simulateur/montant-mensuel-cotisation/age/' + age + '/salaire/' + salaire + '/montantMensuelIndemnise/' + montantMensuelIndemnise + '/dureeIndemnisation/' + dureeIndemnisation,
        method: "GET",
        dataType: "json"
    });
    request.done(function (data) {
        if (data.hasOwnProperty('status') && data.status === "success") {
            $('#duree-montant-mensuel-cotisations .irs-min').text(data.mentantMensuelCotisationDureeMin + " €");
            $('#duree-montant-mensuel-cotisations .irs-max').text(data.mentantMensuelCotisationDureeMax + " €");
            $('#duree-montant-mensuel-cotisations .irs-single').text(data.mentantMensuelCotisation + " €");
            $('#montantCotisation .montant').html(data.mentantMensuelCotisation + " €");
            var dataChart1 = [data.tauxCouverturePoleEmploi, data.tauxCouvertureMadp, parseFloat(data.tauxCouvertureNonCouvert), 0];
            var dataChart2 = [data.tauxCouverturePoleEmploi + data.tauxCouvertureMadp, parseFloat(data.tauxCouvertureNonCouvert)];
            var chartConfig = getcouvertureChartPieConfig(dataChart1, dataChart2);
            initCouvertureChartPie(chartConfig);
        }

        if (data.hasOwnProperty('status') && data.status === "error") {

        }

    });
    request.fail(function (jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}
/**
 *
 * @param {object} $ jQuery
 * @param {int} indeminiteMensuelleMin
 * @param {int} plafontIndeminiteMensuelleMax
 * @param {int} indeminiteMensuellePas
 * @returns {void}
 */
function initInputMontantMonsuelIndemniseRange($, indeminiteMensuelleMin, plafontIndeminiteMensuelleMax, indeminiteMensuellePas) {
    var slider = jQuery("#input-montantMonsuelIndemnise").data("ionRangeSlider");
    if (indeminiteMensuelleMin > indeminiteMensuellePas) {
        indeminiteMensuelleMin = indeminiteMensuelleMin - indeminiteMensuellePas;
    } else {
        indeminiteMensuelleMin = 0;
    }
    if (typeof slider === 'undefined') {
        $('#input-montantMonsuelIndemnise').ionRangeSlider({
            grid: true,
            min: indeminiteMensuelleMin,
            max: plafontIndeminiteMensuelleMax,
            step: indeminiteMensuellePas,
            from: plafontIndeminiteMensuelleMax,
            prefix: "€",
            values: getRangeSteps(indeminiteMensuelleMin, plafontIndeminiteMensuelleMax, indeminiteMensuellePas),
            onFinish: function (data) {
                updateMontantMensuelDesCotisations($);
            }
        });
    } else {
        slider.update({
            min: indeminiteMensuelleMin,
            max: plafontIndeminiteMensuelleMax,
            step: indeminiteMensuellePas,
            from: plafontIndeminiteMensuelleMax,
            values: getRangeSteps(indeminiteMensuelleMin, plafontIndeminiteMensuelleMax, indeminiteMensuellePas)
        });
    }
}


/**
 *
 * @param {object} $ jQuery
 * @param {int} indeminiteMensuelleMin
 * @param {int} plafontIndeminiteMensuelleMax
 * @param {int} indeminiteMensuellePas
 * @returns {void}
 */
function initSelectMontantMonsuelIndemniseRange($, indeminiteMensuelleMin, plafontIndeminiteMensuelleMax, indeminiteMensuellePas) {
    if (indeminiteMensuelleMin > indeminiteMensuellePas) {
        indeminiteMensuelleMin = indeminiteMensuelleMin - indeminiteMensuellePas;
    } else {
        indeminiteMensuelleMin = 0;
    }
    var options = getRangeSteps(indeminiteMensuelleMin, plafontIndeminiteMensuelleMax, indeminiteMensuellePas);
    var selectedOption = plafontIndeminiteMensuelleMax;
    var select = $('#select-montantMonsuelIndemnise');
    $('option', select).remove();

    $.each(options, function (val, text) {
        $(select).append(new Option(numberWithSpaces(text) + ' €', text, false, false));
    });
    $(select).val(selectedOption);
}
/**
 *
 * @param {object} $ jQuery
 * @param {int} dureeIndemnisationMin
 * @param {int} dureeIndemnisationMax
 * @param {int} dureeIndemnisationDefault
 * @returns {void}
 */
function initInputinputDureeCotisationsRange($, dureeIndemnisationMin, dureeIndemnisationMax, dureeIndemnisationDefault) {

    var slider = jQuery("#input-duree-montant-mensuel-cotisations").data("ionRangeSlider");
    var values = getRangeSteps(dureeIndemnisationMin - 1, dureeIndemnisationMax, 1);
    var from = values.indexOf(dureeIndemnisationDefault);
    if (typeof slider === 'undefined') {
        $('#input-duree-montant-mensuel-cotisations').ionRangeSlider({
            grid: true,
            min: dureeIndemnisationMin,
            max: dureeIndemnisationMax,
            from: from,
            step: 1,
            prefix: "mois",
            values: getRangeSteps(dureeIndemnisationMin - 1, dureeIndemnisationMax, 1),
            onFinish: function (data) {
                updateMontantMensuelDesCotisations($);
            }
        });
    } else {

    }
}
/**
 *
 * @param {object} $ jQuery
 * @param {int} dureeIndemnisationMin
 * @param {int} dureeIndemnisationMax
 * @param {int} dureeIndemnisationDefault
 * @returns {void}
 */
function initSelectDureeCotisationsRange($, dureeIndemnisationMin, dureeIndemnisationMax, dureeIndemnisationDefault) {
    var options = getRangeSteps(dureeIndemnisationMin - 1, dureeIndemnisationMax, 1);
    var selectedOption = dureeIndemnisationDefault;
    var select = $('#select-duree-montant-mensuel-cotisations');
    $('option', select).remove();

    $.each(options, function (val, text) {
        $(select).append(new Option(text + ' mois', text, false, false));
    });
    $(select).val(selectedOption);

}


/**
 * IsValidSimulateurEcran1
 * @param {object} $ jQuery
 * @returns {Boolean}
 */
function IsValidSimulateurEcran1($) {
    var bool = true;
    if ($('#field_age input').val().length < 1) {
        bool = false;
        $('#field_age input').val('');
        $('#field_age').addClass('has-error');
        $('#field_age .help-required').removeClass('hidden');
    } else {
        $('#field_age').removeClass('has-error');
        $('#field_age .help-required').addClass('hidden');
    }

    if ($('#field_salaire_annuel_brut input').val().length < 1) {
        bool = false;
        $('#field_salaire_annuel_brut input').val('');
        $('#field_salaire_annuel_brut').addClass('has-error');
        $('#field_salaire_annuel_brut .help-required').removeClass('hidden');
    } else {
        $('#field_salaire_annuel_brut').removeClass('has-error');
        $('#field_salaire_annuel_brut .help-required').addClass('hidden');
    }

    return bool;
}

/**
 *
 * @param {int} min
 * @param {int} max
 * @param {int} step
 * @returns {Array|getRangeSteps.values}
 */
function getRangeSteps(min, max, step) {
    var values = [];
    var i = min;
    do {
        i += step;
        values.push(i);
    } while (i < max);
    return values;
}


function initCouvertureChartPie(config) {
    if (typeof window.couverturePie !== "undefined") {
        window.couverturePie.destroy();
    }
    if (typeof window.couverturePieMobile !== "undefined") {
        window.couverturePieMobile.destroy();
    }
    var ctx = document.getElementById("taux-couverture-chart-area").getContext("2d");
    window.couverturePie = new Chart(ctx, config);

    var ctx_mobile = document.getElementById("taux-couverture-chart-area-mobile").getContext("2d");
    window.couverturePieMobile = new Chart(ctx_mobile, config);

    document.getElementById('pie-legends').innerHTML = window.couverturePie.generateLegend();
}

/**
 *
 * chart plugin to add percent
 */
if (typeof Chart !== 'undefined') {
    Chart.plugins.register({
        afterDatasetsDraw: function (chartInstance, easing) {
            if (chartInstance.config.type === "pie") {
                var ctx = chartInstance.chart.ctx;
                var sum = 0;
                chartInstance.data.datasets.forEach(function (dataset, i) {
                    var meta = chartInstance.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function (element, index) {
                            var label = chartInstance.data.labels[index];
                            if (label === 'Non couvert') {
                                ctx.fillStyle = '#8e8e8e';
                            } else {
                                ctx.fillStyle = 'white';
                            }


                            var fontSize = 16;
                            var fontStyle = 'bold';
                            var fontFamily = 'Gotham-Bold, sans-serif';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                            var dataString2 = dataset.data[index];
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            var padding = 5;
                            var position = element.tooltipPosition();



                            //ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                            if (Number(dataString2) > 10 && dataset.label !== "Dataset 2") {
                                ctx.fillText(Number(dataString2).toFixed(2) + "%", position.x, position.y - (fontSize / 2) - padding + fontSize);
                            }

                            sum += dataset.data[index];
                        });
                    }
                });

            }
        }
    });
}

function getcouvertureChartPieConfig(data1, data2) {
    var config = {
        type: 'pie',
        data: {
            datasets: [{
                    data: data1,
                    backgroundColor: [
                        'rgb(29, 198, 177)',
                        'rgb(239, 102, 47)',
                        'rgb(205, 205, 205)',
                        'rgb(5, 101, 106)'
                    ],
                    label: 'Dataset 1',
                    labels: [
                        "Pôle emploi",
                        "Complémentaire chômage",
                        "Non couvert",
                        "Couverture Global"
                    ],
                },
                {
                    data: data2,
                    backgroundColor: [
                        'rgb(5, 101, 106)',
                        'rgb(205, 205, 205)'
                    ],
                    label: 'Dataset 2',
                    labels: [
                        "Couverture Global",
                        "Non couvert"
                    ],
                }],
            labels: [
                "Pôle emploi",
                "Complémentaire chômage",
                "Non couvert",
                "Couverture Global"
            ]
        },
        options: {
            responsive: true,
            legend: false,
            maintainAspectRatio: true,
            tooltips: {
                mode: 'single',
                callbacks: {
                    label: function (tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var currentValue = dataset.data[tooltipItem.index];
                        return Number(currentValue).toFixed(2) + ' %';
                    }
                }
            }
        }
    };
    return config;
}
