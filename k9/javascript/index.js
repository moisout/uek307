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
    });

    $('.save-btn').on('click', function () {
        var form = new SettingsStorage();
        form.saveForm();
    });

    $('.load-btn').on('click', function () {
        var form = new SettingsStorage();
        form.loadForm();
    });
});


function SettingsStorage() {
    this.formData = {
        vorname: $('#vorname').val(),
        nachname: $('#nachname').val(),
        email: $('#email').val(),
        password: $('#password').val(),
        date: $('#date').val(),
        time: $('#time').val(),
        gender: $('input[name=gender]').val(),
        checker: $('input[name=gender]').val(),
        selection: $('#selection>option:selected').val()
    }


    this.saveForm = function () {
        localStorage['formData'] = JSON.stringify(this.formData);
        console.log(localStorage['formData']);
    }

    this.loadForm = function(){
        console.log(localStorage['formData']);
        this.formData = JSON.parse(localStorage['formData']);
        $('#vorname').val(this.formData.vorname);
        $('#nachname').val(this.formData.nachname);
        $('#email').val(this.formData.email);
        $('#password').val(this.formData.password);
        $('#date').val(this.formData.date);
        $('#time').val(this.formData.time);
        $('input[name=gender]').val(this.formData.gender);
        $('input[name=gender]').val(this.formData.checker);
        $('#selection>option:selected').val(this.formData.selection);
    }

    this.printForm = function () {
        console.debug(
            this.formData
        )
    }
}

jQuery.fn.extend({
    outerHTML: function () {
        return jQuery('<div />').append(this.eq(0).clone()).html();
    }
});