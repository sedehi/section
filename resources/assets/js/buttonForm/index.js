import * as $ from 'jquery';
import bootbox from 'bootbox';

export default (function () {
    if ($('.convert-form').length) {
        $('body').after().append('<form id="convert-form"></form>');
        $('#convert-form').css('display', 'none');
        $(document).on('click','.convert-form', function (e) {
            e.preventDefault();
            var method = $(this).data('method');
            var form_not_support = ['delete', 'put', 'patch'];

            if (!method) {
                method = 'post';
            }

            method = method.toLowerCase();

            if (form_not_support.indexOf(method) !== -1) {
                $('#convert-form').append('<input type="hidden" name="_method" value="' + method.toUpperCase() + '">');
            }

            if (form_not_support.indexOf(method) !== -1) {
                method = 'post';
            }

            if (method !== 'get') {
                var csrf_token = $('meta[name=csrf-token]').attr('content');
                $('#convert-form').append('<input type="hidden" name="_token" value="' + csrf_token + '" />');
            }

            $('#convert-form').append($(this).find('input').clone());
            $('#convert-form').attr('method', method);
            $('#convert-form').attr('action', $(this).data('action'));

            bootbox.confirm({
                message: $(this).data('confirm-question'),
                buttons: {
                    confirm: {
                        className: 'btn-danger'
                    },

                },
                callback: function (result) {
                    if (result) {
                        $('#convert-form').submit();
                    }
                }
            });
        });
    }
}())
