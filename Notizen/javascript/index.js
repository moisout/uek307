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
        }
    });
}

function editNote(id) {
    $('#reset').click();
    let modal = M.Modal.getInstance($('#modal'));
    let note = $(`#note_${id}`);
    $('#noteForm').attr('current-record', id);

    $('#noteTitle').siblings('label').addClass('active');
    $('#noteContent').siblings('label').addClass('active');

    $('#noteTitle').val(note.find('.noteTitle').html());
    $('#noteContent').val(note.find('.noteContent').text());

    modal.open();
}

function newNote() {
    $('#reset').click();
    let modal = M.Modal.getInstance($('#modal'));
    $('#noteForm').attr('current-record', 'none');

    modal.open();
}

$('#modal').submit(function (e) {
    let intent = $('#noteForm').attr('current-record');
    let title = $('#noteTitle').val();
    let content = $('#noteContent').val();

    if (intent === 'none') {
        $.ajax({
            type: "PUT",
            url: "notes.php",
            data: {
                action: 'putdata',
                title: title,
                content: content
            },
            dataType: "json",
            success: function (response) {
                reloadNotes(response);
                let modal = M.Modal.getInstance($('#modal'));

                $('#reset').click();

                modal.close();
            }
        });
    } else {
        $.ajax({
            type: "POST",
            url: "notes.php",
            data: {
                action: 'postdata',
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