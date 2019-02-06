$(function () {
    $('.sidenav').sidenav();
    $('.tooltipped').tooltip();

    $('.timepicker').timepicker();
    $('select').formSelect();

    $('#msg-btn').on('click', function () {
        M.toast({
            html: 'Message sent'
        });
        $('.colordiv').addClass('grey');
    });

    $('.date-year').html(`© ${new Date().getFullYear()} Maurice Oegerli`);

    // datePicker.setDate(new Date(Date.now()));

    $('.datepicker').datepicker({
        defaultDate: new Date(Date.now()),
    });

    $('#date').on('change', function () {
        var datePicker = M.Datepicker.getInstance($('#date'));
        var dateString = datePicker.toString();
        var date = new Date(dateString);

        console.log($('#date').val());
    });
    // $('#name').val('Name');
    // $('#vorname').addClass('valid');
    // $('#password').val('1234');
    // $('#password').addClass('valid');
    // $('#email').val('test@test.ch');
    // $('#email').addClass('valid');

    $('#password').on('keyup', function () {
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

        var asd = new SettingsStorage();
        asd.printForm();
    });
});

function SettingsStorage(){
    this.vorname = $('#vorname').val();
    this.nachname = $('#nachname').val();
    this.email = $('#email').val();
    this.password = $('#password').val();
    this.date = $('#date').val();
    this.time = $('#time').val();
    this.gender = $('input[name=gender]').val();
    this.checker = $('input[name=gender]').val();
    this.selection = $('#selection>option[selected]').val();

    // this.saveForm = function(){
    //     this.vorname = $('#vorname').val();
    //     this.nachname = 
    // }

    this.printForm = function(){
        console.debug(
            this.vorname,

        )
    }
}

jQuery.fn.extend({
    outerHTML: function () {
        return jQuery('<div />').append(this.eq(0).clone()).html();
    }
});