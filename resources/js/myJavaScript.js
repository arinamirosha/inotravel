$('document').ready(function () {
    $('#menu a').each(function () {
        if ($(this).attr('href') == window.location.href)
            $(this).css('text-decoration', 'underline');
    });

    $("#file").change(function (e) {
        e.preventDefault();
        var formData = new FormData();
        var form = $("#form-file-ajax");
        var file = $(this).prop('files')[0];

        var msg = $('#message');
        msg.fadeOut();
        $('#imgError').fadeOut();

        if (file) {
            formData.append('file', file);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                data: formData,
                beforeSend: function () {
                    $('#process').fadeIn();
                },
                complete: function () {
                    $('#process').fadeOut();
                },
                success: function (data) {
                    $('#photo').attr('src', window.location.origin + '/storage/' + data.image);
                    $('#imgId').val(data.id);
                    $('#deletePhoto').fadeIn();
                    $('#deleteImage').prop('checked', false);
                },
                error: function (data) {
                    var response = JSON.parse(data.responseText);
                    var errorMessage = response.errors['file'][0];
                    msg.html(errorMessage);
                    msg.fadeIn();
                }
            });
        }
    });

    $('#deletePhoto').click(function () {
        $('#photo').attr('src', window.location.origin + '/images/noImage.svg');
        $('#imgId').val('');
        $('#message').fadeOut();
        $('#deletePhoto').fadeOut();
        $('#deleteImage').prop('checked', true);
    });

    $("#filter-form").submit(function (e) {
        e.preventDefault();
        getHistory(1);
    });

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var page = href.split('page=')[1];
        if (href.includes('history')) {
            getHistory(page);
        } else {
            getData(page);
        }
    });
});

$(window).on('hashchange', function () {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        } else {
            var href = $(this).attr('href'); // undefined?!
            if (href.includes('history')) {
                getHistory(page);
            } else {
                getData(page);
            }
        }
    }
});

function getHistory(page) {
    var form = $('#filter-form');
    $.ajax({
        url: form.attr('action') + '?page=' + page,
        type: form.attr('method'),
        processData: false,
        contentType: false,
        cache: false,
        data: form.serialize(),
        success: function (data) {
            $('#filter-result').empty().html(data);
            $('#city, #arrival, #departure').removeClass('is-invalid');
            $('#errorCity, #errorArrival, #errorDeparture').text('');
            location.hash = page;
        },
        error: function (data) {
            var errors = JSON.parse(data.responseText).errors;

            $('#city, #arrival, #departure').removeClass('is-invalid');
            $('#errorCity, #errorArrival, #errorDeparture').text('');

            if (errors['city']) {
                $('#city').addClass('is-invalid');
                $('#errorCity').text(errors['city']);
            }
            if (errors['arrival']) {
                $('#arrival').addClass('is-invalid');
                $('#errorArrival').text(errors['arrival']);
            }
            if (errors['departure']) {
                $('#departure').addClass('is-invalid');
                $('#errorDeparture').text(errors['departure']);
            }
        }
    });
}

function getData(page) {
    $.ajax({
        url: '?page=' + page,
        type: "get",
        datatype: "html"
    }).done(function (data) {
        $("#result_wrap").empty().html(data);
        location.hash = page;
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
    });
}

