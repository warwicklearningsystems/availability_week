YUI.add('moodle-availability_week-form', function (Y, NAME) {

/**
 * JavaScript for form editing date conditions.
 *
 * @module moodle-availability_week-form
 */
M.availability_week = M.availability_week || {};

/**
 * @class M.availability_week.form
 * @extends M.core_availability.plugin
 */
M.availability_week.form = Y.Object(M.core_availability.plugin);

/**
 * Initialises this plugin.
 *
 * Because the date fields are complex depending on Moodle calendar settings,
 * we create the HTML for these fields in PHP and pass it to this method.
 *
 * @method initInner
 * @param {String} html HTML to use for date fields
 * @param {Number} defaultTime Time value that corresponds to initial fields
 */
M.availability_week.form.initInner = function(html, defaultTime) {
    this.html = html;
    this.defaultTime = defaultTime;
};

M.availability_week.form.getNode = function(json) {
    var html = '<span class="col-form-label p-r-1">' +
                    M.util.get_string('direction_before', 'availability_week') + '</span> <span class="availability-group">' +
            '<label><span class="accesshide">' + M.util.get_string('direction_label', 'availability_week') + ' </span>' +
            '<select name="direction" class="custom-select">' +
            '<option value="&gt;=">' + M.util.get_string('direction_before', 'availability_week') + '</option>' +
            '<option value="&lt;">' + M.util.get_string('direction_until', 'availability_week') + '</option>' +
            '</select></label></span> ' + this.html;
    var node = Y.Node.create('<span>' + html + '</span>');

    // Set initial value if non-default.
    if (json.t !== undefined) {
        node.setData('time', json.t);
        // Disable everything.
        node.all('select:not([name=direction])').each(function(select) {
            select.set('disabled', true);
        });

        var select = node.one('select[name=week-select');
        select.set('value', '' + json.t);
        select.set('disabled', false);

    } else {
        // Set default time.
        node.setData('time', this.defaultTime);
    }
    if (json.d !== undefined) {
        node.one('select[name=direction]').set('value', json.d);
    }

    // Add event handlers (first time only).
    if (!M.availability_week.form.addedEvents) {
        M.availability_week.form.addedEvents = true;

        var root = Y.one('.availability-field');
        root.delegate('change', function() {
            // For the direction, just update the form fields.
            M.core_availability.form.update();
        }, '.availability_week select[name=direction]');

        root.delegate('change', function() {
            // Set time with value of this option.
            this.ancestor('span.availability_week').setData('time', this.get("value"));
            M.core_availability.form.update();
        }, '.availability_week select:not([name=direction])');
    }


    return node;
};


M.availability_week.form.fillValue = function(value, node) {
    value.d = node.one('select[name=direction]').get('value');
    value.t = parseInt(node.getData('time'), 10);
};


}, '@VERSION@', {"requires": ["base", "node", "event", "io", "moodle-core_availability-form"]});
