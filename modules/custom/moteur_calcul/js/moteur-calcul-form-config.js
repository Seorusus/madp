jQuery(document).ready(function ($) {

    var request = $.ajax({
        url: '/admin/config/moteur_calcul/parameterstable/cotisationTable',
        method: "GET",
        dataType: "json"
    });
    request.done(function (data) {
        if (data.hasOwnProperty('status') && data.status === "success") {
            $('#taux_cotisation_params_table').html(data.content);
            initSliderRange($);
        }
    });
    request.fail(function (jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });

    $('.form-item-taux-cotisation-params-json').hide();// hidden json field
});

(function ($) {
    $(document).on('click', '#taux_cotisation_params_table table .addButton', function () {
        event.preventDefault();
        event.stopPropagation();
        var $container = $(this).closest('table').children('tbody');
        var index = $container.find('tr').length;
        collectionAddRow($, $container, index);
        $('.age_inclus').last().ionRangeSlider({
            type: "double",
            grid: true,
            min: 0,
            max: 100,
            from: 0,
            to: 0,
            onChange: function (data) {
                var data = convertCotisationParamsTableToJson($);
                var dataJson = JSON.stringify(data);
                setTauxCotisationParamsJson(dataJson);
            }
        });
        autoSetTauxCotisationParamsJson($);
    });

})(jQuery);


(function ($) {
    $(document).on('click', '#taux_cotisation_params_table table .removeButton', function () {
        event.preventDefault();
        event.stopPropagation();
        $(this).closest('tr').remove();
        autoSetTauxCotisationParamsJson($);
    });

})(jQuery);
(function ($) {
    $(document).on('change', '.age_augmentation,#edit-taux-cotisation-1,#edit-tranche-indemnisation-1-augmentation,#edit-tranche-indemnisation-2-augmentation,#edit-tranche-indemnisation-3-augmentation', function () {
        event.preventDefault();
        event.stopPropagation();
        autoSetTauxCotisationParamsJson($);
    });

})(jQuery);

(function ($) {
    $(document).on('click', '#parameters-table-form input[type="submit"]', function () {
        event.preventDefault();
        event.stopPropagation();
        autoSetTauxCotisationParamsJson($);
        $('#parameters-table-form').submit();
        return false;
    });

})(jQuery);

function initSliderRange($) {
    $('.age_inclus').each(function (index) {
        $(this).ionRangeSlider({
            type: "double",
            grid: true,
            min: 0,
            max: 100,
            onChange: function (data) {
                var data = convertCotisationParamsTableToJson($);
                var dataJson = JSON.stringify(data);
                setTauxCotisationParamsJson(dataJson);
            }
        });
    });
}

/**
 * add new row to table
 * @param {object} $ jQuery
 * @param {object} $container
 * @param {integer} index
 * @returns {void}
 */
function collectionAddRow($, $container, index) {
    var $prototype = $($container.attr('data-prototype').replace(/__name__/g, index));
    $container.append($prototype);
    index++;
}

function convertCotisationParamsTableToJson($) {
    var data = [];
    $('#taux_cotisation_params_table table tbody tr').each(function (index) {
        var age_range = $(this).find('input.age_inclus').val();
        var augmentation = parseInt($(this).find('input.age_augmentation').val());
        var taux_tr1 = parseFloat($(this).find('span.taux_tr1').text());
        var taux_tr2 = parseFloat($(this).find('span.taux_tr2').text());
        var taux_tr3 = parseFloat($(this).find('span.taux_tr3').text());
        data.push({'age_range': age_range, 'augmentation': augmentation, 'taux_tr1': taux_tr1, 'taux_tr2': taux_tr2, 'taux_tr3': taux_tr3});
    });
    return data;
}


function calculTauxCotisation($) {
    $('#taux_cotisation_params_table table tbody tr').each(function (index) {

        var augmentationTrancheIndemnisation1 = getAugmentationTrancheIndemnisation1($);
        var augmentationTrancheIndemnisation2 = getAugmentationTrancheIndemnisation2($);
        var augmentationTrancheIndemnisation3 = getAugmentationTrancheIndemnisation3($);
        var tauxCotisationMinimum = getTauxCotisationMinimum($);
        var age_augmentation = parseInt($(this).find('input.age_augmentation').val());
        if (index === 0) {
            var tauxCotisationTranche1 = tauxCotisationMinimum + ((age_augmentation / 100) * tauxCotisationMinimum) + ((augmentationTrancheIndemnisation1 / 100) * tauxCotisationMinimum);
        } else {
            var $precedentRow = $('#taux_cotisation_params_table table tbody').find('tr').eq(index - 1);
            var precedentTauxCotisationTranche1 = parseFloat($($precedentRow).find('span.taux_tr1').text());
            var tauxCotisationTranche1 = precedentTauxCotisationTranche1 + ((age_augmentation / 100) * precedentTauxCotisationTranche1) + ((augmentationTrancheIndemnisation1 / 100) * precedentTauxCotisationTranche1);
        }

        var tauxCotisationTranche2 = tauxCotisationTranche1 + ((augmentationTrancheIndemnisation2 / 100) * tauxCotisationTranche1);
        var tauxCotisationTranche3 = tauxCotisationTranche2 + ((augmentationTrancheIndemnisation3 / 100) * tauxCotisationTranche2);
        $(this).find('span.taux_tr1').text(tauxCotisationTranche1.toFixed(4));
        $(this).find('span.taux_tr2').text(tauxCotisationTranche2.toFixed(4));
        $(this).find('span.taux_tr3').text(tauxCotisationTranche3.toFixed(4));
        $(this).find('span.taux_tr1_round').text(roundDecimal(tauxCotisationTranche1, 2));
        $(this).find('span.taux_tr2_round').text(roundDecimal(tauxCotisationTranche2, 2));
        $(this).find('span.taux_tr3_round').text(roundDecimal(tauxCotisationTranche3, 2));

    });
}

function autoSetTauxCotisationParamsJson($) {
    calculTauxCotisation($);
    var data = convertCotisationParamsTableToJson($);
    var dataJson = JSON.stringify(data);
    setTauxCotisationParamsJson(dataJson);

}

function setTauxCotisationParamsJson(dataJson) {
    jQuery('#edit-taux-cotisation-params-json').val(dataJson);
}

function getAugmentationTrancheIndemnisation1($) {
    return parseFloat($('#edit-tranche-indemnisation-1-augmentation').val());
}
function getAugmentationTrancheIndemnisation2($) {
    return parseFloat($('#edit-tranche-indemnisation-2-augmentation').val());
}
function getAugmentationTrancheIndemnisation3($) {
    return parseFloat($('#edit-tranche-indemnisation-3-augmentation').val());
}
function getTauxCotisationMinimum($) {
    return parseFloat($('#edit-taux-cotisation-1').val());
}

function roundDecimal(nombre, precision) {
    var precision = precision || 2;
    var tmp = Math.pow(10, precision);
    return Math.round(nombre * tmp) / tmp;
}


