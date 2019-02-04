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
        data: "data",
        dataType: "json",
        success: function (data){
            $('#seite2').html(data.data);
        }
    });
});