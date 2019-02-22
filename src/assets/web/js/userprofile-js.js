$(document).ready(function () {
    //attiva l'evento di evidenza delle tab dopo il validate del form
    FormActions.afterValidate();

    //abilita i menu di provincia e comune di nascita solo se è stata selezionata come nazione ITALIA
    var nomeDisabilitato = $('#userprofile-nome').prop('readonly');
    if (nomeDisabilitato) {
        
    } else {
        var stato = $('#nascita_nazioni_id').val();
        var statoRes = $('#residenza_nazione_id').val();
        if (stato != 1) {
            $('#nascita_province_id-id').val('');
            $('#nascita_province_id-id').attr('disabled', 'disabled');
        }
        if (statoRes != 1) {
            $('#provincia_residenza_id-id').val('');
            $('#provincia_residenza_id-id').attr('disabled', 'disabled');
        }
    }
    
    $('#nascita_nazioni_id').change(function () {
        
        var newstato = $('#nascita_nazioni_id').val();
        if (newstato == 1) {
            $('#nascita_province_id-id').removeAttr('disabled');
        }
        else {
            $('#nascita_province_id-id').select2("val", '');
            $('#nascita_comuni_id-id').val("").change();
            $('#nascita_province_id-id').attr('disabled', 'disabled');
            $('#nascita_comuni_id-id').attr('disabled', 'disabled');
        }
    });
    
    $('#residenza_nazione_id').change(function () {
 
        var newstatores = $('#residenza_nazione_id').val();
        if (newstatores == 1) {
            $('#provincia_residenza_id-id').removeAttr('disabled');
        }
        else {
            $('#provincia_residenza_id-id').select2("val", '');
            $('#comune_residenza_id-id').val("").change();
            $('#provincia_residenza_id-id').attr('disabled', 'disabled');
            $('#comune_residenza_id-id').attr('disabled', 'disabled');
        }
    });

});
