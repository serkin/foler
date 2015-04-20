
var codes = {
    el: document.getElementById("codes_block"),
    deleteCode: function(id) {

    },

    selectCode: function(idCode) {

    },

    renderWithData: function(arr) {

    },

    clear: function() {
        this.el.innerHTML = '';
    },
    
    MessageForm: {
        clear:  function() {},
        save:   function(code) {}
    },
    
    
    SearchField: {
        el: document.getElementById("search_field"),
        clear:      function() {},
        find:       function(keyword) {},
        setValue:   function(value) {},
        getValue:   function() {}        
    }
};