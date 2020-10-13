$('document').ready(function() {
    $('#menu a').each(function() {
        if ($(this).attr('href') == window.location.href)
            $(this).css('text-decoration', 'underline');
    });

    $("#file").change(function(e){
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
                cache:false,
                dataType : 'text',
                data: formData,
                beforeSend: function(){
                    $('#process').fadeIn();
                },
                complete: function () {
                    $('#process').fadeOut();
                },
                success: function(data){
                    data = JSON.parse(data);
                    $('#photo').attr('src', window.location.origin+'/storage/'+data.image);
                    $('#imgId').val(data.id);
                    $('#deletePhoto').fadeIn();
                    $('#deleteImage').prop('checked', false);
                },
                error: function(data){
                    var response = JSON.parse(data.responseText);
                    var errorMessage = response.errors['file'][0];
                    msg.html(errorMessage);
                    msg.fadeIn();
                }
            });
        }

    });

    $('#deletePhoto').click(function() {
        $('#photo').attr('src', window.location.origin+'/images/noImage.svg');
        $('#imgId').val('');
        $('#message').fadeOut();
        $('#deletePhoto').fadeOut();
        $('#deleteImage').prop('checked', true);
    });

});
