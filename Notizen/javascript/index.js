$(function () {
    $('.sidenav').sidenav();
    $('.parallax').parallax();
    $('.tooltipped').tooltip();
    $('.tabs').tabs();
    $('select').formSelect();
    $('.modal').modal();

    $('#modal').removeClass('open');

    $.ajax({
        type: "GET",
        url: "notes.php",
        data: {
            action: 'getdata'
        },
        dataType: "json",
        success: function (response) {
            reloadNotes(response);
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
});

function reloadNotes(data) {
    $('#note-template').show();
    let template = $('#note-template').outerHTML();

    $('.notes-container').html(template);

    data.forEach((element, index) => {
        let noteElement = Mustache.to_html(template, element);

        $(noteElement).prependTo('.notes-container').prop('id', `note_${element.id}`);
        $(`note_${element.id}`).children();
    });

    $('#note-template').hide();
}

function deleteNote(id) {
    $.ajax({
        type: "DELETE",
        url: "notes.php",
        data: {
            action: 'deletedata',
            id: id
        },
        dataType: "json",
        success: function (response) {
            reloadNotes(response);
            let modal = M.Modal.getInstance($('#modal'));

            $('#reset').click();

            modal.close();
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
}

function editNote(id) {
    $('#reset').click();
    let modal = M.Modal.getInstance($('#modal'));
    let note = $(`#note_${id}`);
    $('#currentRecord').val(id);

    $('#noteTitle').siblings('label').addClass('active');
    $('#noteContent').siblings('label').addClass('active');

    $('#noteTitle').val(note.find('.noteTitle').html());
    $('#noteContent').val(note.find('.noteContent').text());

    modal.open();
}

function newNote() {
    $('#reset').click();
    let modal = M.Modal.getInstance($('#modal'));
    $('#currentRecord').val('none');

    modal.open();
}

$('#modal').submit(function (e) {
    let intent = $('#currentRecord').val();
    let title = $('#noteTitle').val();
    let content = $('#noteContent').val();

    if (intent === '') {
        $.ajax({
            type: "POST",
            url: "notes.php",
            data: {
                action: 'postdata',
                title: title,
                content: content
            },
            dataType: "json",
            success: function (response) {
                reloadNotes(response);
                let modal = M.Modal.getInstance($('#modal'));

                $('#reset').click();

                modal.close();
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
    } else {
        $.ajax({
            type: "PUT",
            url: "notes.php",
            data: {
                action: 'putdata',
                id: intent,
                title: title,
                content: content
            },
            dataType: "json",
            success: function (response) {
                reloadNotes(response);
                let modal = M.Modal.getInstance($('#modal'));

                $('#reset').click();

                modal.close();
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
    }
    e.preventDefault();
});

jQuery.fn.extend({
    outerHTML: function () {
        return jQuery('<div />').append(this.eq(0).clone()).html();
    }
});