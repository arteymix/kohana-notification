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
                .text(notification.message);
    },
    /**
     * Override this function to redefine error formatting.
     * 
     * @param {type} field
     * @param {type} errors
     * @returns {undefined}
     */
    formatErrors: function(field, errors) {

        // Resolve the field

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
        
        // Remove error on blur
        input.blur(function() {
            $(this).parents('.control-group').first().removeClass('error');
        });

        var controlGroup = input.parents('.control-group').addClass('error');

        $.each(errors, function(index, message) {
            controlGroup.append('<span class="help-inline">' + message.charAt(0).toUpperCase() + message.slice(1) + '.</span>');
        });
    }

};

(function($) {
    $(document).ready(function() {

        for (index in Notification.notifications) {
            $('#notifications').append(Notification.format(Notification.notifications[index]));
        }

        $.each(Notification.errors, Notification.formatErrors);

    });
})(window.jQuery);