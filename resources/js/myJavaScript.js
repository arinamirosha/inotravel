$('document').ready(function() {
    $('#menu a').each(function() {
        if ($(this).attr('href') == window.location.href)
            $(this).css('text-decoration', 'underline');
    });
});
