import * as $ from 'jquery';

require('./persian-datepicker.min');
import 'persian-datepicker/dist/css/persian-datepicker.min.css'

export default (function () {
    $('.date ,.date-time').each(function (k, v) {
        var timePicker, format;
        if ($(this).hasClass('date-time')) {
            timePicker = true;
            format = ($(this).data('format') === undefined) ? "YYYY-MM-DD HH:mm:ss" : $(this).data('format');
        } else {
            timePicker = false;
            format = ($(this).data('format') === undefined) ? "YYYY-MM-DD" : $(this).data('format');
        }
        var calendarType = ($('html').attr('lang') == 'fa' ? "persian" : "gregorian");
        if ($(this).data('type') !== undefined) {
            calendarType = $(this).data('type');
        }
        if (calendarType == 'gregorian') {
            $(this).css('direction', 'ltr');
        }

        $(this).pDatepicker({
            "inline": false,
            "format": format,
            "viewMode": "day",
            "initialValue": false,
            "minDate": null,
            "maxDate": null,
            "autoClose": true,
            "position": "auto",
            "altFormat": "X",
            "altField": $(this).data('alt'),
            "onlyTimePicker": false,
            "onlySelectOnDate": true,
            "calendarType": calendarType,
            "inputDelay": 800,
            "observer": false,
            "calendar": {
                "persian": {
                    "locale": "fa",
                    "showHint": false,
                    "leapYearMode": "algorithmic"
                },
                "gregorian": {
                    "locale": "en",
                    "showHint": false
                }
            },
            "navigator": {
                "enabled": true,
                "scroll": {
                    "enabled": true
                },
                "text": {
                    "btnNextText": "<",
                    "btnPrevText": ">"
                }
            },
            "toolbox": {
                "enabled": true,
                "calendarSwitch": {
                    "enabled": false,
                    "format": "MMMM"
                },
                "todayButton": {
                    "enabled": true,
                    "text": {
                        "fa": "امروز",
                        "en": "Today"
                    }
                },
                "submitButton": {
                    "enabled": true,
                    "text": {
                        "fa": "تایید",
                        "en": "Submit"
                    }
                },
                "text": {
                    "btnToday": "امروز"
                }
            },
            "timePicker": {
                "enabled": timePicker,
                "step": 1,
                "hour": {
                    "enabled": timePicker,
                    "step": null
                },
                "minute": {
                    "enabled": timePicker,
                    "step": null
                },
                "second": {
                    "enabled": timePicker,
                    "step": null
                },
                "meridian": {
                    "enabled": false
                }
            },
            "dayPicker": {
                "enabled": true,
                "titleFormat": "YYYY MMMM"
            },
            "monthPicker": {
                "enabled": true,
                "titleFormat": "YYYY"
            },
            "yearPicker": {
                "enabled": true,
                "titleFormat": "YYYY"
            },
            "responsive": true,
        });
    });
}())
