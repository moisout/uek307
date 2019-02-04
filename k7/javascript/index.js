$(function () {
    $('.custom-range-field>input').on('input', function () {
        var inputfield = $(this);
        var width = inputfield.width();
        var steps = inputfield.prop('max') - inputfield.prop('min');
        var value = inputfield.val();
        var sectionWidth = width / steps;
        $('.custom-range-label').css('transform', `translateX(${value * sectionWidth}px)`);
    });
});