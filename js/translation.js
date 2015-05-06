

var translation = {
    save: function(idCode) {

        var data = $('#translationForm').serialize();
        console.log(data);

        sendRequest('translation/save', {form:data}, function(response){
            translation.render(idCode);
        });

    },

    render: function(idCode) {
        sendRequest('translation/getone', {id_code: idCode}, function(response){

            var template = $('#translationFormTemplate').html();
            var rendered = Mustache.render(template, response);

            $('#translationFormBlock').html(rendered);
        });

    },

    clear: function() {
        this.el.innerHTML = '';
    }
};