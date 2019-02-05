$(function () {
    $('.sidenav').sidenav();
    $('.parallax').parallax();
    $('.tooltipped').tooltip();
    $('.tabs').tabs();
    $('select').formSelect();


    $('#msg-btn').on('click', function () {
        M.toast({
            html: 'Message sent'
        });
        $('.colordiv').addClass('grey');
    });

    $('.date-year').html(`© ${new Date().getFullYear()} Maurice Oegerli`);

    $('.colorpicker-custom').on('change', function(){
        $('.colorpicker-custom-btn').css('background-color', $('.colorpicker-custom').val());
        $('.custom-label-input').val($('.colorpicker-custom').val());
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

    function autoLaden(autos) {
        autos.forEach(element => {
            var datensatz = $(`#auto_liste_${element.id}`);

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
            $(`#auto_liste_${id}`).find('.tank').html( tankfuellung + 1);

        });
        $('.edit-btn').on('click', function () {
            M.toast({
                html: 'Bearbeitet'
            });
        });
        $('.delete-btn').on('click', function () {
            var id = $(this).parent().attr('data-id');
            $(`#auto_liste_${id}`).remove();

            M.toast({
                html: 'Gelöscht'
            });
        });
    }
});

function returnId(id) {
    console.log(id);
}

jQuery.fn.extend({
    outerHTML: function () {
        return jQuery('<div />').append(this.eq(0).clone()).html();
    }
});