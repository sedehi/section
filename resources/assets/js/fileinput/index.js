import * as $ from 'jquery';
import 'bootstrap-fileinput/js/fileinput.min';
import 'bootstrap-fileinput/js/locales/fa';
import 'bootstrap-fileinput/themes/explorer-fa/theme.min.css'
import 'bootstrap-fileinput/themes/explorer-fa/theme.min'

export default (function () {
    $.fn.fileinputLocales['fa'].browseLabel = 'انتخاب فایل';
    var initialPreview = [];
    var initialPreviewConfig = [];
    $('.upload-files').each(function () {
        initialPreview.push($(this).data('url'));
        var configs = $.extend({}, $(this).data(), {showDrag: false});
        configs['url'] = configs['deleteUrl'];
        delete configs['deleteUrl'];
        initialPreviewConfig.push(configs);
    });

    $(".upload-input").fileinput({
        showUpload: false,
        theme: "explorer-fa",
        overwriteInitial: false,
        initialPreviewAsData: true,
        previewFileType: 'any',
        showCaption: false,
        removeClass: "btn btn-danger btn-block",
        browseClass: "btn btn-primary btn-block",
        language: ($('html').attr('lang') == 'fa') ? 'fa' : 'en',
        rtl: ($('html').attr('dir') == 'rtl') ? 'rtl' : 'ltr',
        initialPreview: initialPreview,
        initialPreviewConfig: initialPreviewConfig,
        ajaxDeleteSettings: {
            method: 'delete',
        }
    });
}());
