/**
 * errors and notifications should be initialized in the <head> tag before the
 * document ready event is launched. If you want to handle notifications
 * yourself, just don't initialize them :P
 * 
 * You can redefine how notifications are presented by overrding the format
 * function. It will be appended to #notifications.
 * 
 * Add a copy of this file in your static files.
 * 
 * @type Notification
 * 
 * @author  Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license BSD 3 clauses
 */
var Notification = {
    DANGER: 'danger',
    SUCCESS: 'success',
    INFO: 'info',
    WARNING: 'warning',
    errors: {},
    notifications: [],
    /**
     * Add a new notification.
     * 
     * @param   string level
     * @param   string message
     * @returns jQuery the contexted of the appended notification
     */
    add: function(level, message) {

        notification = {
            'level': level,
            'message': message
        };

        this.notifications += [notification];

        return $('#notifications').append(Notification.format(notification)).children(':last');
    },
    /**
     * Add errors on a field.
     * 
     * @param {type} input
     * @param {type} errors
     * @returns {undefined}
     */
    addErrors: function(field, errors) {

        if (field in this.errors) {
            this.errors[field] = $.merge(Notification.errors[field], errors);
        } else {
            this.errors[field] = errors;
        }

        this.formatError(field, errors);
    },
    /**
     * Override this function to redefine notification formatting.
     * 
     * @param object      notification
     * @returns {jQuery}3 an object or string representing the generated HTML.
     */
    format: function(notification) {

        return $('<div/>')
                .attr(notification.attributes)
                .addClass('alert')
                .addClass('alert-' + notification.level)
                .html(notification.message);
    },
    /**
     * Override this function to redefine error formatting.
     * 
     * This methods appends error using Bootstrap.
     * 
     * @param {type} field
     * @param {type} errors
     * @returns {undefined}
     */
    applyErrors: function(field, errors) {

        // Resolve the field by priority

        // equals field
        var input = $("[name='" + field + "']");

        // ends with [field]
        if (input.size() === 0) {
            input = $("[name$='[" + field + "]']");
        }

        // contains [field] somewhere
        if (input.size() === 0) {
            input = $("[name*='[" + field + "]']");
        }

        // begins with field
        if (input.size() === 0) {
            input = $("[name^='" + field + "']");
        }

        var controlGroup = input.parents('.form-group').first().addClass('text-danger');

        errors = $.isArray(errors) ? errors : [errors];

        $.each(errors, function(index, message) {

            if ($.isArray(message)) {

                for (subField in message) {

                    subField = (field === '_external') ? subField : field + '[' + subField + ']';

                    Notification.applyErrors(subField, message[subField]);

                }

            } else {

                controlGroup.append($('<p/>').html(message));
            }

        });
    }

};

(function($) {
    $(document).ready(function() {

        for (index in Notification.notifications) {
            $('#notifications').append(Notification.format(Notification.notifications[index]));
        }

        $.each(Notification.errors, Notification.applyErrors);

    });
})(window.jQuery);