

function sendRequest(action, data, callback) {
    var request = new XMLHttpRequest();
    request.open('POST', 'foler.php?action='+action, true);
    request.onload = function() {
    if (request.status >= 200 && request.status < 400) {
      // Success!
      callback(JSON.parse(request.responseText));

    } else {
      // We reached our target server, but it returned an error

    }
  };
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    
    request.send(data);
}