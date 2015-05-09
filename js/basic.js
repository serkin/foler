


function sendRequest(action, data, callback) {

    $.ajax({
            type: 'post',
            url: 'foler.php?action='+action,
            data: data,
            error: function()	{
                alert('Connection lost');
            },
            success: function(response)	{
                if(callback !== "undefined"){
                    callback(response);
                }
            }
        });
}
