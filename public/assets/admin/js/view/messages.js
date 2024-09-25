function messageSend(btn) {
    const identifier = btn.getAttribute('data-identifier');
    executeAjax({
        url: common.API_URI + '/messageSend/'+identifier,
        headers: {
            'Authorization' : common.HOOK_PHPTOJS_VAR_TOKEN,
        },
        after: {
            callback: showAlert,
            params: {
                type: 'success',
            },
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.warn(jqXHR.responseJSON)
            showAlert({
                type: 'warning',
                text: jqXHR.responseJSON.msg,
            });
        }
    });
}

function messageRead(btn) {
    const identifier = btn.getAttribute('data-identifier');
    executeAjax({
        url: common.API_URI + '/messageRead/'+identifier,
        headers: {
            'Authorization' : common.HOOK_PHPTOJS_VAR_TOKEN,
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response)
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.warn(jqXHR.responseJSON)
            showAlert({
                type: 'warning',
                text: jqXHR.responseJSON.msg,
            });
        }
    });
}
