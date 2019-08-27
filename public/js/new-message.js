function formArrayToObject(array) {
    var obj = {};
    for(var i = 0; i != array.length; i++) {
        var name = array[i].name;
        var value = array[i].value;
        obj[name] = value;
    }
    return obj;
}

function toggleLoader() {
    $('#submit-message').toggle();
    $('#loader').toggle();
}

function toggleDisabled($field, disable) {
    $field.prop('readonly', disable);
    $field.css('pointer-events', disable ? 'none' : 'initial');
}

function clearMarkedFields() {
    $('.message-field').each(function() {
        $(this).parent().removeClass('err');
        toggleDisabled($(this), false);
    });
}

function markField(valid, $field) {
    if(valid) {
        toggleDisabled($field, true);
    } else {
        $field.parent().addClass('err');
    }
}

function processFields(fields) {
    for(var name in fields) {
        var selector = '.message-field[name=' + name + ']';
        var $field = $(selector);
        markField(fields[name].valid, $field);
        $field.val(fields[name].value);
    }
}

function handleLastMessage() {
    var perPage = $('#message-list').data('per-page');
    var messages = $('#message-list li');
    if(messages.length > perPage) {
        messages.last().remove();
    }
}

function addNewMessage(html) {
    if($('#empty-message').length > 0) {
        $('#empty-message').remove();
        $('#message-list').append(html);
    } else {
        $('#message-list').prepend(html);
        handleLastMessage();
    }
}

function clearFields() {
    $('.message-field').val(null);
}

function onResponse(response) {
    clearMarkedFields();
    toggleLoader();
    if(!response.valid) {
        processFields(response.errors);
    } else {
        addNewMessage(response.html);
        clearFields();
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
        toggleLoader();
        $.post('post-ajax.php', data, onResponse, 'json');
    });
});
