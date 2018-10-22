function showErrorMessage(msg) {
    Messenger({
        extraClasses: 'messenger-fixed messenger-on-bottom',
        theme: 'future'
    }).post({
        message: msg,
        type: 'error',
        hideAfter: 3,
        showCloseButton: false
    });
}

function progressMessage() {
    var i = 0;
    Messenger({
        extraClasses: 'messenger-fixed messenger-on-right messenger-on-top',
        theme: 'flat'
    }).run({
        errorMessage: 'Error destroying alien planet. Retrying...',
        successMessage: 'Alien planet destroyed!',
        action: function(opts) {
            if (++i < 2) {
                return opts.error({
                    status: 500,
                    readyState: 0,
                    responseText: 0
                });
            } else {
                return opts.success();
            }
        }
    });
}

function showSuccess(msg) {
    Messenger({
        extraClasses: 'messenger-fixed messenger-on-right messenger-on-top',
        theme: 'flat'
    }).post(msg);
}
