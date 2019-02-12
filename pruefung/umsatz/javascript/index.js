$(function () {
    // --------------------------------------------------------------------------------------------
    // Initialisierung der CSS-Komponenten
    // --------------------------------------------------------------------------------------------
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
    $('.sidenav').sidenav();
    $('.modal').modal({
        onCloseEnd: function () {
            $('#umsatzForm').attr('current-record', 'none');
            $('#reset').click();
        }
    });

    $('.date-year').html(`© ${new Date().getFullYear()} Maurice Oegerli`);

    // --------------------------------------------------------------------------------------------
    // Einträge werden geladen
    // --------------------------------------------------------------------------------------------
    $.ajax({
        type: "GET",
        url: "umsatz.php",
        data: {
            action: 'getdata',
        },
        dataType: "json",
        success: function (data) {
            eintraegeLaden(data);
        },
        error: function (data) {
            var response = data.responseJSON;
            response.forEach(element => {
                M.toast({
                    html: `Fehler bei ${element}`
                })
            });
        }
    });

    $('.modal-trigger').on('click', function () {
        $('.modal-title').html('Eintrag erstellen');
        $('#reset').click();
    });

    // --------------------------------------------------------------------------------------------
    // Funktion fürs (Neu) laden
    // --------------------------------------------------------------------------------------------
    function eintraegeLaden(data) {

        $('.umsatz-list').html($('#umsatz_liste_template'));
        $('#umsatz_liste_template').show();
        data.forEach(element => {
            var template = $('#umsatz_liste_template').outerHTML();
            var date = element.umsatz_kunde_seit.split('-');
            var dateFormatted = `${date[2]}.${date[1]}.${date[0]}`;
            element.umsatz_kunde_seit = dateFormatted;

            var datensatz = Mustache.to_html(template, element);
            $(datensatz).appendTo('.umsatz-list').prop('id', `umsatz_liste_${element.id}`);
            $(`#umsatz_liste_${element.id}`).find('.name>i').css('color', element.farbe);
        });

        $('#umsatz_liste_template').hide();

        // --------------------------------------------------------------------------------------------
        // Bearbeiten eines Eintrags
        // --------------------------------------------------------------------------------------------
        $('.edit-btn').on('click', function () {
            var umsatz_id = $(this).parents('.umsatz-list-content').attr('data-id');
            $('#umsatzForm').attr('current-record', umsatz_id);
            var modal = M.Modal.getInstance($('#modal'));
            $('.modal-title').html('Eintrag bearbeiten');

            $('#umsatz_kunde_name').siblings('label').addClass('active');
            $('#umsatz_filiale').siblings('label').addClass('active');
            $('#umsatz_umsatz').siblings('label').addClass('active');
            $('#umsatz_kunde_seit').siblings('label').addClass('active');
            $('#umsatz_anzbestellungen').siblings('label').addClass('active');

            $.ajax({
                type: "GET",
                url: "umsatz.php",
                data: {
                    action: 'getdatabyid',
                    umsatz_id: umsatz_id
                },
                dataType: "json",
                success: function (response) {
                    $('#umsatz_kunde_name').val(response[0].umsatz_kunde_name);
                    $('#umsatz_filiale').val(response[0].umsatz_filiale);
                    $('#umsatz_umsatz').val(response[0].umsatz_umsatz);
                    $('#umsatz_kunde_seit').val(response[0].umsatz_kunde_seit);
                    $('#umsatz_anzbestellungen').val(response[0].umsatz_anzbestellungen);

                    modal.open();
                }
            });
        });

        // --------------------------------------------------------------------------------------------
        // Löschen eines Eintrags
        // --------------------------------------------------------------------------------------------
        $('.delete-btn').on('click', function () {
            var umsatz_id = $(this).parents('.umsatz-list-content').attr('data-id');
            $.ajax({
                type: "DELETE",
                url: "umsatz.php",
                data: {
                    umsatz_id: umsatz_id,
                    action: 'deletedata',
                },
                dataType: 'json',
                success: function (data) {
                    eintraegeLaden(data);
                },
                error: function (data) {
                    var response = data.responseJSON;
                    response.forEach(element => {
                        M.toast({
                            html: `Fehler bei ${element}`
                        })
                    });
                }
            });

            M.toast({
                html: `Eintrag mit Id ${umsatz_id} Gelöscht`
            });
        });
    }

    // --------------------------------------------------------------------------------------------
    // Formular absenden
    // --------------------------------------------------------------------------------------------
    $('#umsatzForm').submit(function (e) {
        var umsatz_kunde_name = $('#umsatz_kunde_name').val();
        var umsatz_filiale = $('#umsatz_filiale').val();
        var umsatz_umsatz = $('#umsatz_umsatz').val();
        var umsatz_kunde_seit = $('#umsatz_kunde_seit').val();
        var umsatz_anzbestellungen = $('#umsatz_anzbestellungen').val();

        var umsatz_id = $('#umsatzForm').attr('current-record');

        if (umsatz_id == 'none') {
            // --------------------------------------------------------------------------------------------
            // Eintrag erstellen
            // --------------------------------------------------------------------------------------------
            $.ajax({
                type: 'POST',
                url: "umsatz.php",
                data: {
                    action: 'postdata',
                    umsatz_kunde_name: umsatz_kunde_name,
                    umsatz_filiale: umsatz_filiale,
                    umsatz_umsatz: umsatz_umsatz,
                    umsatz_kunde_seit: umsatz_kunde_seit,
                    umsatz_anzbestellungen: umsatz_anzbestellungen
                },
                dataType: 'json',
                success: function (data) {
                    eintraegeLaden(data);
                    $('#reset').click();
                    M.Modal.getInstance($('#modal')).close();
                    M.toast({
                        html: `${umsatz_kunde_name} erstellt`
                    });
                },
                error: function (data) {
                    var response = data.responseJSON;
                    response.forEach(element => {
                        M.toast({
                            html: `Fehler bei ${element}`
                        });
                        $(`#${element}`).addClass('invalid');
                    });
                }
            });
        } else {
            // --------------------------------------------------------------------------------------------
            // Eintrag bearbeiten
            // --------------------------------------------------------------------------------------------
            $.ajax({
                type: 'PUT',
                url: "umsatz.php",
                data: {
                    action: 'putdata',
                    umsatz_id: umsatz_id,
                    umsatz_kunde_name: umsatz_kunde_name,
                    umsatz_filiale: umsatz_filiale,
                    umsatz_umsatz: umsatz_umsatz,
                    umsatz_kunde_seit: umsatz_kunde_seit,
                    umsatz_anzbestellungen: umsatz_anzbestellungen
                },
                dataType: 'json',
                success: function (data) {
                    eintraegeLaden(data);
                    $('#reset').click();
                    M.Modal.getInstance($('#modal')).close();
                    M.toast({
                        html: `${umsatz_kunde_name} bearbeitet`
                    });
                },
                error: function (data) {
                    var response = data.responseJSON;
                    response.forEach(element => {
                        M.toast({
                            html: `Fehler bei ${element}`
                        });
                        $(`#${element}`).addClass('invalid');
                    });
                }
            });
        }

        e.preventDefault();
    });
});

jQuery.fn.extend({
    outerHTML: function () {
        return jQuery('<div />').append(this.eq(0).clone()).html();
    }
});