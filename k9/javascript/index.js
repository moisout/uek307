$(function () {
    $('.sidenav').sidenav();
    $('.tooltipped').tooltip();
    $('.datepicker').datepicker();
    $('.timepicker').timepicker();
    $('select').formSelect();

    $('#msg-btn').on('click', function () {
        M.toast({
            html: 'Message sent'
        });
        $('.colordiv').addClass('grey');
    });

    $('.date-year').html(`© ${new Date().getFullYear()} Maurice Oegerli`);


    // $('#name').val('Name');
    // $('#vorname').addClass('valid');
    // $('#password').val('1234');
    // $('#password').addClass('valid');
    // $('#email').val('test@test.ch');
    // $('#email').addClass('valid');

    $('#password').on('keyup', function(){
        var password = $('#password').val();
        var hasNoHigherCase = password === password.toLowerCase();

        if ($('#password').val().length < 8) {
            $('#password').addClass('invalid');
        }
        if (hasNoHigherCase) {
            $('#password').addClass('invalid');
        }
        if ($('#password').val().length > 8 && !hasNoHigherCase) {
            $('#password').removeClass('invalid');
            $('#password').addClass('valid');
        }
    });

    $('.testForm').submit(function (e) {
        var password = $('#password').val();
        var hasNoHigherCase = password === password.toLowerCase();

        if ($('#password').val().length < 8) {
            $('#password').addClass('invalid');
            M.toast({
                html: 'Passwort muss aus mindestens 8 Buchstaben bestehen'
            });
        }
        if (hasNoHigherCase) {
            $('#password').addClass('invalid');
            M.toast({
                html: 'Passwort muss mindestens einen Grossbuchstaben beinhalten'
            });
        }
        if ($('#password').val().length > 8 && !hasNoHigherCase) {
            M.toast({
                html: 'Message sent'
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