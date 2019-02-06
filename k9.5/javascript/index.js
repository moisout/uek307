$(function () {
    $('.sidenav').sidenav();
    $('.parallax').parallax();
    $('.tooltipped').tooltip();
    $('.tabs').tabs();
    $('select').formSelect();
    $('.modal').modal({
        onCloseEnd: function(){
            $('#carForm').attr('current-record', 'none');
            console.log('test');
        }
    });

    $('#msg-btn').on('click', function () {
        M.toast({
            html: 'Message sent'
        });
        $('.colordiv').addClass('grey');
    });

    $('.date-year').html(`© ${new Date().getFullYear()} Maurice Oegerli`);

    $('.colorpicker-custom').on('change', function () {
        $('.colorpicker-custom-btn').css('background-color', $('.colorpicker-custom').val());
        $('.custom-label-input').val($('.colorpicker-custom').val());
        $('.custom-label').addClass('active');
    });

    $.ajax({
        type: "GET",
        url: "autos.php",
        data: {
            action: 'getdata'
        },
        dataType: "json",
        success: function (data) {
            autoLaden(data);
        }
    });

    var me = this;

    function autoLaden(autos) {
        $('.auto-list').html($('#auto_liste_template'));
        $('#auto_liste_template').show();
        autos.forEach(element => {
            var template = $('#auto_liste_template').outerHTML();

            var datensatz = Mustache.to_html(template, element);
            $(datensatz).appendTo('.auto-list').prop('id', `auto_liste_${element.id}`);
            $(`#auto_liste_${element.id}`).find('.name>i').css('color', element.farbe);
        });

        $('#auto_liste_template').hide();

        $('.fuel-btn').on('click', function () {
            M.toast({
                html: 'Betankt'
            });

            var id = $(this).parent().attr('data-id');
            var tankfuellung = parseInt($(`#auto_liste_${id}`).find('.tank').html());
            $(`#auto_liste_${id}`).find('.tank').html(tankfuellung + 1);

        });
        $('.edit-btn').on('click', function () {
            var id = $(this).parent().attr('data-id');
            $('#carForm').attr('current-record', id);
            var modal = M.Modal.getInstance($('#modal'));

            var autoListe = $(`#auto_liste_${id}`);

            $('#name').val(autoListe.find('.name').text());
            $('#kraftstoff').val(autoListe.find('.kraftstoff').html());
            $('#bauart').val(autoListe.find('.bauart').html());
            $('#colorfield').val(autoListe.find('.farbe').html());

            modal.open();
        });
        $('.delete-btn').on('click', function () {
            var id = $(this).parent().attr('data-id');
            $.ajax({
                type: "DELETE",
                url: "autos.php",
                data: {
                    action: 'deletedata',
                    id: id,
                },
                dataType: 'json',
                success: function (data) {
                    autoLaden(data);
                }
            });

            M.toast({
                html: 'Gelöscht'
            });
        });
    }

    $('#carForm').submit(function (e) {
        var name = $('#name').val();
        var kraftstoff = $('#kraftstoff').val();
        var bauart = $('#bauart').val();
        var farbe = $('#colorfield').val();
        var id = $('#carForm').attr('current-record');

        if (id == 'none') {
            $.ajax({
                type: 'PUT',
                url: "autos.php",
                data: {
                    action: 'putdata',
                    name: name,
                    kraftstoff: kraftstoff,
                    bauart: bauart,
                    farbe: farbe
                },
                dataType: 'json',
                success: function (data) {
                    autoLaden(data);
                    $('#reset').click();
                    M.Modal.getInstance($('#modal')).close();
                }
            });
        } else {
            $.ajax({
                type: 'POST',
                url: "autos.php",
                data: {
                    action: 'editdata',
                    id: id,
                    name: name,
                    kraftstoff: kraftstoff,
                    bauart: bauart,
                    farbe: farbe
                },
                dataType: 'json',
                success: function (data) {
                    autoLaden(data);
                    $('#reset').click();
                    M.Modal.getInstance($('#modal')).close();
                }
            });
        }

        e.preventDefault();
    });
});

function returnId(id) {
    console.log(id);
}

jQuery.fn.extend({
    outerHTML: function () {
        return jQuery('<div />').append(this.eq(0).clone()).html();
    }
});