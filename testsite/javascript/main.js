function init(){
    $('#title-text').on('input', function(){
        console.log($(this).val());
        $('.title-replace').html($('#title-text').val());
    });

    $('.container').on('mouseenter', function(){
        $('.text-status').html('willkommen');
    });

    $('.container').on('mouseleave', function(){
        $('.text-status').html('bye');
    });
}