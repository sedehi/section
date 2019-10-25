import * as $ from 'jquery';
import * as toastr from 'toastr';
import "toastr/build/toastr.min.css";

export default (function () {
    toastr.options.timeOut = "false";
    toastr.options.closeButton = true;
    if ($('html').attr('dir') == 'rtl') {
        toastr.options.positionClass = 'toast-bottom-left';
        toastr.options.rtl = true;

    } else {
        toastr.options.positionClass = 'toast-bottom-right';

    }
    toastr.options.closeMethod = 'fadeOut';
    toastr.options.closeDuration = 300;
    toastr.options.closeEasing = 'swing';
    toastr.options.preventDuplicates = true;
    toastr.options.timeOut = 7000;
    toastr.options.extendedTimeOut = 60;
    window.toastr = toastr;
    window.jquery = $;
}())
