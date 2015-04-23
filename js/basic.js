
function sendRequest(action, data, callback) {
    var request = new XMLHttpRequest();
    request.open('POST', 'foler.php?action='+action, true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    request.onload = function() {
    if (request.status >= 200 && request.status < 400) {
      // Success!
      callback(JSON.parse(request.responseText));

    } else {
      alert('Connection lost');

    }
  };
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

    request.send('form=' + JSON.stringify(data));
}


function serialize(form)
{
	if (!form || form.nodeName !== "FORM") {
		return;
	}
	var i, j,
		obj = {};
	for (i = form.elements.length - 1; i >= 0; i = i - 1) {
		if (form.elements[i].name === "") {
			continue;
		}
		switch (form.elements[i].nodeName) {
		case 'INPUT':
			switch (form.elements[i].type) {
			case 'text':
			case 'hidden':
			case 'password':
			case 'button':
			case 'reset':
			case 'submit':
				obj[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
				break;
			case 'checkbox':
			case 'radio':
				if (form.elements[i].checked) {
					obj[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
				}
				break;
			case 'file':
				break;
			}
			break;
		case 'TEXTAREA':
			obj[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
			break;
		case 'SELECT':
			switch (form.elements[i].type) {
			case 'select-one':
				obj[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
				break;
			case 'select-multiple':
				for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
					if (form.elements[i].options[j].selected) {
						obj[form.elements[i].name] = encodeURIComponent(form.elements[i].options[j].value);
					}
				}
				break;
			}
			break;
		case 'BUTTON':
			switch (form.elements[i].type) {
			case 'reset':
			case 'submit':
			case 'button':
				obj[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
				break;
			}
			break;
		}
	}
	return obj;
}