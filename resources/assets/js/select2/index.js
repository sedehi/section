import * as $ from 'jquery';
import "select2/dist/js/select2.full.min";
import "select2/dist/js/i18n/fa";
import "select2/dist/js/i18n/en";
import "select2/dist/css/select2.min.css";
import "select2-bootstrap-theme/dist/select2-bootstrap.min.css";

export default (function () {

    function addQueryString(url, queryString) {
        if (queryString) {
            var isQuestionMarkPresent = url && url.indexOf('?') !== -1,
                separator = isQuestionMarkPresent ? '&' : '?';
            url += separator + queryString;
        }

        return url;
    }

    $.fn.select2.defaults.set("theme", "bootstrap");
    $.fn.select2.defaults.set("placeholder", ".....");
    $.fn.select2.defaults.set("allowClear", true);
    $.fn.select2.defaults.set("language", $('html').attr('lang'));
    $.fn.select2.defaults.set("dir", $('html').attr('dir'));
    $('.select2').each(function () {
        var options = {};
        if (!(typeof $(this).data('url') === "undefined")) {
            options = {
                ajax: {
                    url: $(this).data('url'),
                    dataType: 'json',
                    delay: 300,
                    cache: false,
                    data: function (params) {
                        return {
                            search: params.term,
                            withTrashed: $('input[name=' + $(this).attr('name') + '_with_trashed]').is(':checked')
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.resources,
                            pagination: {
                                more: data.more
                            }
                        };
                    }
                }
            }
        }
        if (!$(this).data('searchable')) {
            options.minimumResultsForSearch = -1;
        }

        var select2 = $(this).select2(options);

        if (!(typeof $(this).data('value') === "undefined") && !(typeof $(this).data('url') === "undefined")) {
            var value = $(this).data('value');
            if (value > 0) {
                $.ajax({
                    url: $(this).data('url') + '?key=' + value, success: function (result) {
                        if (result.resources.length) {
                            var oldData = result.resources[0];
                            var option = new Option(oldData.text, oldData.id, false, false);
                            select2.append(option).trigger('change');
                        }
                    }
                });
            }

        }

    });
}())
