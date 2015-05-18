
var codes = {

    deleteCode: function(code, searchKeyword) {
        sendRequest('code/delete', {code:code, id_project: idSelectedProject}, function(response){

            statusField.render(response.status);
            codes.SearchField.find(searchKeyword);
            translation.render();

        });

    },

    selectCode: function(code, el) {
        $('.code_block').removeClass('success');
        el.addClass('success');
        translation.render(code);
    },
    
    CodeForm: {
        save:   function(code) {
            sendRequest('code/save', {code:code, id_project: idSelectedProject});
        },
        render: function() {

            var template = $('#codeFormTemplate').html();
            var rendered = Mustache.render(template);

            $('#codeFormBlock').html(rendered);
        },
        hide:   function() {
            $('#codeFormBlock').html('');
        }
    },
    
    
    SearchField: {

        find:   function(keyword) {
            sendRequest('code/search', {keyword:keyword, id_project: idSelectedProject}, function(response){

            var template = $('#codesTemplate').html();
            var rendered = Mustache.render(template, response.data);

            $('#codesBlock').html(rendered);
        });
        },
        show: function() {
            $('#searchKeyword').show();
        },
        hide: function() {
            $('#searchKeyword').hide();
        }
        
    }
};