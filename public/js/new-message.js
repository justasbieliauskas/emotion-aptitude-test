function formArrayToObject(array) {
    var obj = {};
    for(var i = 0; i != array.length; i++) {
        var name = array[i].name;
        var value = array[i].value;
        obj[name] = value;
    }
    return obj;
}

function prependNewMessage(messageHtml) {
    $('#message-list').prepend(messageHtml);
}

function clearMarkedFields() {
    $('.message-field').parents('p').each(function() {
        $(this).removeClass('err');
    });
}

function markFields(fields) {
    for(var name in fields) {
        var valid = fields[name];
        if(!valid) {
            var selector = '.message-field[name=' + name + ']';
            $(selector).parent().addClass('err');
        }
    }
}

function onResponse(response) {
    clearMarkedFields();
    if(!response.valid) {
        markFields(response.errors);
    } else {
        alert('Valid.');
    }
}

$(document).ready(function () {
    $.fn.serializeObject = function() {
        var array = $(this).serializeArray();
        return formArrayToObject(array);
    };

    $('#message-form').submit(function (e) {
        e.preventDefault();
        var data = $(this).serializeObject();
        $.post('post-ajax.php', data, onResponse, 'json');
    });
});
