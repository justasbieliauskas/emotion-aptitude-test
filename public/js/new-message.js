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

function disableField($field, disable) {
    $field.prop('readonly', disable);
    $field.css('pointer-events', disable ? 'none' : 'initial');
}

function processField(valid, $field) {
    var $parent = $field.parent();
    if(valid) {
        if($parent.hasClass('err')) {
            $parent.removeClass('err');
        }
    } else {
        disableField($field, false);
        $parent.addClass('err');
    }
}

function processFields(fields) {
    for(var name in fields) {
        var selector = '.message-field[name=' + name + ']';
        var $field = $(selector);
        $field.val(fields[name].value);
        processField(fields[name].valid, $field);
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

function disableAllFields(disable) {
    $('.message-field').each(function() {
        disableField($(this), disable);
    });
}

function removeMarkings() {
    $('.message-field').parent('p').removeClass('err');
}

function onResponse(response) {
    toggleLoader();
    if(!response.valid) {
        processFields(response.errors);
    } else {
        addNewMessage(response.html);
        clearFields();
        disableAllFields(false);
        removeMarkings();
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
        disableAllFields(true);
        $.post('post-ajax.php', data, onResponse, 'json');
    });
});
