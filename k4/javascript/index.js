$(function () {
    $('.sidenav').sidenav();
    $('.parallax').parallax();
    $('.tooltipped').tooltip();
    $('.tabs').tabs();


    $('#msg-btn').on('click', function () {
        M.toast({
            html: 'Message sent'
        });
        $('.colordiv').addClass('grey');
    });

    $('.date-year').html(`© ${new Date().getFullYear()} Maurice Oegerli`);

    $('#seite1').load('components/page1.html');

    $.ajax({
        type: "GET",
        url: "components/page2.json",
        dataType: "json",
        success: function (data) {
            $('#seite2').html(data.data);
        }
    });

    $.ajax({
        type: "GET",
        url: "components/autos.json",
        dataType: "json",
        success: function (data) {
            autoLaden(data);
        }
    });

    async function autoLaden(autos) {
        autos.forEach(element => {
            await new Promise(resolve => $.ajax({
                type: 'GET',
                url: 'components/auto-template.html',
                dataType: 'html',
                success: function (data) {
                    $('.auto-list').append(data);
                    $('#auto_liste_template').prop('id', `auto_liste_${element.id}`);

                    ['id', 'name', 'kraftstoff', 'farbe', 'bauart', 'tank']
                    .forEach(item => {
                        $(`#auto_liste_${element.id}`).find(`.${item}`).html(element[item]);
                    });
                    resolve(data);
                }
            }));
        });
    }

});