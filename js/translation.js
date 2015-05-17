

var translation = {
    save: function(code) {

        var data = $('#translationForm').serialize();

        sendRequest('translation/save', {form:data}, function(response){
            statusField.render(response.status);
            translation.render(code);
        });

    },

    render: function(code) {

        var data = (code !== "undefined") ? {code: code, id_project:idSelectedProject} : {id_project:idSelectedProject};

        sendRequest('translation/getone', data, function(response){
            
            response.data.id_project = idSelectedProject;

            var template = $('#translationFormTemplate').html();
            var rendered = Mustache.render(template, response.data);

            $('#translationFormBlock').html(rendered);
        });

    }
};