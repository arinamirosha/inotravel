const AVATAR = 'avatar';
const HOUSE_IMAGE = 'houseImage';

$('document').ready(function () {
    if (typeof user !== 'undefined') {
        Echo.private('user.'+user.id)
            .listen('NewBookingEvent', (e) => {

                $.ajax({
                    url: '/toast',
                    type: 'post',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ev: JSON.stringify(e)
                    },
                    success: function (data) {
                        $('#toast-container').append(data);
                        $('.toast').last().toast('show');
                    },
                    error: function (data) {
                        formatErrors(data);
                    }
                });

                let news = $('#newInBooks');
                if (news.html() === '') {
                    news.html('(+1)');
                } else {
                    let count = parseInt(news.html().substring(2, news.html().length-1)) + 1;
                    news.html(`(+${count})`);
                }
            });
    }

    $('#menu a').each(function () {
        if ($(this).attr('href') == window.location.href)
            $(this).css('text-decoration', 'underline');
    });

    $("#file").change(function (e) {
        e.preventDefault();
        uploadImageAjax($(this), HOUSE_IMAGE);
    });

    $("#avatar").change(function (e) {
        e.preventDefault();
        uploadImageAjax($(this), AVATAR);
    });

    $('#deletePhoto').click(function () {
        $('#photo').attr('src', window.location.origin + '/images/noImage.svg');
        $('#imgId').val('');
        $('#message').fadeOut();
        $('#deletePhoto').fadeOut();
        $('#deleteImage').prop('checked', true);
    });

    $('#deleteAvatar').click(function () {
        $('#message').fadeOut();
        form = $('#form-file-ajax');
        var formData = new FormData();
        formData.append('delete', 'on');
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            beforeSend: function () {
                $('#process').fadeIn();
            },
            complete: function () {
                $('#process').fadeOut();
            },
            success: function (data) {
                $('#photo').attr('src', window.location.origin + '/images/noImage.svg');
                $('#deleteAvatar').addClass('invisible');
            },
            error: function (data) {
                alert(data);
            }
        });
    });

    $("#filter-form").submit(function (e) {
        e.preventDefault();
        getHistory(1);
    });

    $("#search-form").submit(function (e) {
        e.preventDefault();
        getSearch(1);
    });

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var page = href.split('page=')[1];
        if (href.includes('history')) {
            getHistory(page);
        } else if (href.includes('search')) {
            getSearch(page);
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
            var href = window.location.href;
            if (href.includes('history')) {
                getHistory(page);
            } else if (href.includes('search')) {
                getSearch(page);
            } else {
                getData(page);
            }
        }
    }
});

function uploadImageAjax(input, type) {
    var formData = new FormData();
    var form = $("#form-file-ajax");
    var file = input.prop('files')[0];

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
                switch (type) {
                    case HOUSE_IMAGE:
                        $('#imgId').val(data.id);
                        $('#deletePhoto').fadeIn();
                        $('#deleteImage').prop('checked', false);
                        break;
                    case AVATAR:
                        $('#deleteAvatar').removeClass('invisible');
                        break;
                }
            },
            error: function (data) {
                var response = JSON.parse(data.responseText);
                var errorMessage = response.errors['file'][0];
                msg.html(errorMessage);
                msg.fadeIn();
            }
        });
    }
}

function getHistory(page) {
    var form = $('#filter-form');
    $.ajax({
        url: form.attr('action') + '?page=' + page,
        type: form.attr('method'),
        processData: false,
        contentType: false,
        cache: false,
        data: form.serialize(),
        beforeSend: function () {
            $('#process').fadeIn();
        },
        complete: function () {
            $('#process').fadeOut();
        },
        success: function (data) {
            $('#filter-result').empty().html(data);
            $('#city, #arrival, #departure').removeClass('is-invalid');
            $('#errorCity, #errorArrival, #errorDeparture').text('');
            location.hash = page;
        },
        error: function (data) {
            formatErrors(data);
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

function getSearch(page) {
    var form = $('#search-form');
    $.ajax({
        url: form.attr('action') + '?page=' + page,
        type: form.attr('method'),
        processData: false,
        contentType: false,
        cache: false,
        data: form.serialize(),
        beforeSend: function () {
            $('#process').fadeIn();
        },
        complete: function () {
            $('#process').fadeOut();
        },
        success: function (data) {
            $('#search-result').empty().html(data);
            $('#where, #arrival, #departure, #people').removeClass('is-invalid');
            $('#errorWhere, #errorArrival, #errorDeparture, #errorPeople').text('');
            location.hash = page;
        },
        error: function (data) {
            formatErrors(data);
        }
    });
}

function formatErrors(data) {
    var errors = JSON.parse(data.responseText).errors;

    $('#city, #where, #arrival, #departure, #people').removeClass('is-invalid');
    $('#errorCity, #errorWhere, #errorArrival, #errorDeparture, #errorPeople').text('');

    if (errors['city']) {
        $('#city').addClass('is-invalid');
        $('#errorCity').text(errors['city']);
    }
    if (errors['where']) {
        $('#where').addClass('is-invalid');
        $('#errorWhere').text(errors['where']);
    }
    if (errors['arrival']) {
        $('#arrival').addClass('is-invalid');
        $('#errorArrival').text(errors['arrival']);
    }
    if (errors['departure']) {
        $('#departure').addClass('is-invalid');
        $('#errorDeparture').text(errors['departure']);
    }
    if (errors['people']) {
        $('#people').addClass('is-invalid');
        $('#errorPeople').text(errors['people']);
    }
}
