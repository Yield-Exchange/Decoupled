function makeApiCall(url, parameters, successCallback,loader, method='POST', errorCallback = null) {
    $.ajax({
        beforeSend: function () {
            if (loader) {
                loader.show();
            }
        },
        complete: function () {
            if (loader) {
                loader.hide();
            }
        },
        type: method,
        url: url,
        data: parameters,
        // contentType: 'application/json;',
        dataType: 'json',
        success: successCallback,
        error: errorCallback ? errorCallback : function (xhr, textStatus, errorThrown) {
            // console.log(errorThrown);
        }
    });
}

function makeApiCallWithFiles(url, parameters, successCallback,loader, method='POST', errorCallback = null) {
    $.ajax({
        beforeSend: function () {
            if (loader) {
                loader.show();
            }
        },
        complete: function () {
            if (loader) {
                loader.hide();
            }
        },
        type: method,
        url: url,
        data: parameters,
        // contentType: 'application/json;',
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        dataType: 'json',
        success: successCallback,
        error: errorCallback ? errorCallback : function (xhr, textStatus, errorThrown) {
            // console.log(errorThrown);
        }
    });
}

function apiCallServerErrorMessage(xhr,defaultMessage){
    if([400,409,401,403].includes(xhr.status)){
        if(Array.isArray(xhr?.responseJSON?.message)){
            let message="";
            xhr?.responseJSON?.message.map(function (item) {
                message+=item+',';
            });

            return message;
        }
        return xhr?.responseJSON?.message;
    }

    return defaultMessage;
}

$.fn.removeClassStartingWith = function (filter) {
    $(this).removeClass(function (index, className) {
        return (className.match(new RegExp("\\S*" + filter + "\\S*", 'g')) || []).join(' ')
    });
    return this;
};

escapeHtml = function(unsafe) {
    try {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }catch (e) {
        return unsafe;
    }
};

clear_form_elements_values = function($container,reset_defaults_class=[]) {
    $container.find(':input').each(function() {
        let $classes = $(this).attr('class') ? $(this).attr('class').split(' ') : [];
        switch(this.type) {
            case 'password':
            case 'text':
            case 'textarea':
            case 'file':
            case 'select-one':
            case 'select-multiple':
            case 'date':
            case 'number':
            case 'tel':
            case 'email':
                if( reset_defaults_class.length > 0 && $classes.some(r=> reset_defaults_class.includes(r)) ){
                    $(this).val($("option:first",this).val());
                }else {
                    $(this).val('');
                }
                $(this).trigger("change");
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
                break;
        }
    });
};