/* 
 * Copyright (C) 2014 SP CYEND <info@cyend.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (typeof (SPCYEND_core) === 'undefined') {
    var SPCYEND_core = {};
}
if (typeof (interval_var) === 'undefined') {
    var interval_var;
}

/**
 * Transfer selected items
 * 
 * @param {string} task
 * @param {string} form
 * @returns {Boolean}
 */
SPCYEND_core.transfer = function(task, form) {

    if (typeof (form) === 'undefined') {
        form = document.getElementById('adminForm');
    }

    if (typeof (task) !== 'undefined' && task !== "") {
        form.task.value = task;
    }

    //hide table and toobar
    var sptransfer_table = document.getElementById('sptransfer_table');
    sptransfer_table.hidden = true;
    var toolbar = document.getElementById('toolbar');
    toolbar.hidden = true;

    //set message
    var message_div = document.getElementById('cyend_log');
    message_div.hidden = false;
    var message = '<p><img src="../media/com_sptransfer/images/processing.gif" alt="">'
            + ' processing - please wait...</p>';
    message_div.innerHTML = message;

    interval_var = setInterval(function() {
        SPCYEND_core.get_last_id();
    }, 15000);

    //submit form
    AJAXSubmit(form);

    return;

};

/**
 * Handle completion of each request
 * 
 * @param {string} responseText
 * @returns {Boolean|undefined}
 */
SPCYEND_core.completed = function(responseText) {

    var message_div = document.getElementById('cyend_log');
    //decode responseText
    try {
        var result = eval("(" + responseText + ")");
    }
    catch (err)
    {
        message_div.innerHTML = responseText;
        clearInterval(interval_var);
        var div_log = document.getElementById('get_last_id');
        div_log.hidden = true;
        return;
    }

    //get message
    var message_div = document.getElementById('cyend_log');

    clearInterval(interval_var);
    location.reload();
    return;
    
    var div_log = document.getElementById('get_last_id');
    div_log.hidden = true;
    message = '<p>Process Completed.</p>';
    message_div.innerHTML = message;

    return;

};

/**
 * Create randomly two stings
 * 
 * @returns {String}
 */
SPCYEND_core.randomString = function() {
    var result = Math.floor((Math.random() * 2) + 1);
    if (result === 1) {
        return 'please wait...';
    } else {
        return 'processing...';
    }
};

/**
 * Get last id from history log
 * 
 * @returns {undefined}
 */
SPCYEND_core.get_last_id = function() {
    
    var url = 'index.php?option=com_sptransfer&task=log.get_file_log';
    var div_log = document.getElementById('get_last_id');
    div_log.hidden = false;

    var request = new XMLHttpRequest();
    request.open('GET', url, false);  // `false` makes the request synchronous
    try
    {
        request.send(null);
    }
    catch (err)
    {
        clearInterval(interval_var);
        var txt = "There was an error.\n\n";
        txt += "Error description: " + err.message + "\n\n";
        txt += "Click OK to continue.\n\n";
        alert(txt);
    }


    div_log.innerHTML = request.responseText;

};

/**
 * Dummy function that does nothing.
 * 
 * @returns {undefined}
 */
SPCYEND_core.dummy = function() {

    return;

};
