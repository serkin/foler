
var codes = {
    el: document.getElementById("codes_block"),
    deleteCode: function(id) {

    },

    selectCode: function(idCode) {
        $('.code_block').removeClass('success');
        $('#code_block_' + idCode).addClass('success');
        translation.render(idCode);
    },

    renderWithData: function(arr) {

    },

    clear: function() {
        this.el.innerHTML = '';
    },
    
    CodeForm: {
        clear:  function() {},
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
        clear:      function() {},
        find:       function(keyword) {
            sendRequest('code/search', {keyword:keyword, id_project: idSelectedProject}, function(response){

            var template = $('#codesTemplate').html();
            var rendered = Mustache.render(template, response);

            $('#codesBlock').html(rendered);
        });
        },
        setValue:   function(value) {},
        getValue:   function() {}        
    }
};