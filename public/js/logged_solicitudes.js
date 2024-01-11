document.querySelector(`#send-document`)
    .addEventListener('click', () => {
        lockWindow();
        $(`.error-message`).empty();
        let _formData = new FormData($(`#document-form`)[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                }
            });
            $.ajax({
                url : `/logged-solicitude`,
                type: 'POST',
                data: _formData,
                contentType: false,
                processData: false,
                success: function(e){
                    unlockWindow();
                    notice(`${e.title}`, `${e.message}`, `success`);
                    $(`#document-form`)[0].reset();
                    //$(`#order_id`).val(e.id);
                    //$(`#request-completed-form`).submit();
                    setTimeout(function(){ 
                        location.replace(`/admin/solicitudes`);
                     }, 1000);

                },
                error:function(jqXHR, textStatus, errorThrown)
                {
                    notice(`Advertencia`, `Hay errores en uno o más campos.`, `warning`);
                    unlockWindow();
                    $.each(jqXHR.responseJSON, function (key, value) {
                          $.each(value, function (errores, eror) {
                            $(`#document-${key}-error`).append("<li class='error-block'>" + eror + "</li>");
                          });
                    });
                }
            });
    });