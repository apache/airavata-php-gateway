//This script files changes all the table <td> elements with time class to show browser bases local time.
//<td> elements should have the unix time as an attribute e.g. <td class="time" unix-time="423423423524"></td>

function convertTimestamp(timestamp) {
    var d = new Date(timestamp * 1000),	// Convert the passed timestamp to milliseconds
        yyyy = d.getFullYear(),
        mm = ('0' + (d.getMonth() + 1)).slice(-2),	// Months are zero based. Add leading 0.
        dd = ('0' + d.getDate()).slice(-2),			// Add leading 0.
        hh = d.getHours(),
        h = hh,
        min = ('0' + d.getMinutes()).slice(-2),		// Add leading 0.
        ampm = 'AM',
        time;

    if (hh > 12) {
        h = hh - 12;
        ampm = 'PM';
    } else if (hh === 12) {
        h = 12;
        ampm = 'PM';
    } else if (hh == 0) {
        h = 12;
    }

    // ie: 2013-02-18, 8:35 AM
    time = yyyy + '-' + mm + '-' + dd + ', ' + h + ':' + min + ' ' + ampm;

    var offset = new Date().toString().match(/([A-Z]+[\+-][0-9]+.*)/)[1];

    return time + " - " + offset;
}

$(document).ready( function(){
    var elements = document.getElementsByClassName("time"),
    i = elements.length;
    while (i--) {
        elements[i].innerHTML = convertTimestamp(elements[i].getAttribute("unix-time"));
    }
});