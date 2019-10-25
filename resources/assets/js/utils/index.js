import * as $ from 'jquery';

export default (function () {


    // ------------------------------------------------------
    // @External Links
    // ------------------------------------------------------

    // Open external links in new window
    $('a')
    .filter('[href^="http"], [href^="//"]')
    .not(`[href*="${window.location.host}"]`)
    .attr('rel', 'noopener noreferrer')
    .attr('target', '_blank');

    // ------------------------------------------------------
    // @Resize Trigger
    // ------------------------------------------------------


    $(document).ready(function () {
        $(document).on('change', '.check-all', function () {
            $(this).closest('table').find('tbody :checkbox')
            .prop('checked', $(this).is(':checked'))
            .closest('tr').toggleClass('table-active', $(this).is(':checked'));
        });

        $(document).on('change', 'tbody :checkbox', function () {
            $(this).closest('tr').toggleClass('table-active', this.checked);

            $(this).closest('table').find('.check-all').prop('checked', ($(this).closest('table').find('tbody :checkbox:checked').length == $(this).closest('table').find('tbody :checkbox').length));
        });

        $(document).on('change', '.delete-item , .check-all', function () {
            var btn = $(this).closest('.card').find('.delete-btn');
            var deleteForm = $('#delete-form');
            btn.html('<i class="fa fa-trash"></i>');
            deleteForm.html('');

            var csrfToken = $('meta[name=csrf-token]').attr('content');
            deleteForm.append('<input type="hidden" name="_token" value="'+csrfToken+'">');
            deleteForm.append('<input type="hidden" name="_method" value="delete">');
            $(this).closest('table').find('.delete-item:checked').each(function () {
                    deleteForm.append('<input type="hidden" name="deleteId[]" value="' + $(this).val() + '">');
            });

            if ($(this).closest('table').find('.delete-item:checked').length > 0) {
                btn.removeClass('d-none');
            } else {
                btn.addClass('d-none');
            }
        });

        $(document).on('change', '.per-page', function () {
            $(this).parents('form:first').submit();
        });

        $('.sidebar-link').each(function () {
            if ($(this).data('active') == 1) {
                $(this).addClass('active');
                $(this).parents('.dropdown-menu').css('display', 'block');
            }
        });

        $('.table-responsive-stack').find("th").each(function (i) {
            $('.table-responsive-stack td:nth-child(' + (i + 1) + ')').prepend('<span class="table-responsive-stack-thead">' + $(this).text() + ':</span> ');
            $('.table-responsive-stack-thead').hide();
        });


        $('.table-responsive-stack').each(function () {
            var thCount = $(this).find("th").length;
            var rowGrow = 100 / thCount + '%';
            $(this).find('.form-check').css('display', 'inline-flex');
            $(this).find("th, td").css('min-width', rowGrow);
        });


        function flexTable() {
            if ($(window).width() < 768) {
                $(".table-responsive-stack").each(function (i) {
                    $(this).find(".table-responsive-stack-thead").show();
                    $(this).find('thead').hide();
                });
            } else {
                $(".table-responsive-stack").each(function (i) {
                    $(this).find(".table-responsive-stack-thead").hide();
                    $(this).find('thead').show();
                });
            }
        }
            flexTable();

        window.onresize = function (event) {
            flexTable();
        };


        $('.delete-btn').on('click',function(e){
            if(confirm('از حذف اطلاعات اطمینان دارید؟')){
                $('#delete-form').submit();
            }
        });


    });
}());
