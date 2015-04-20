
var statusField = {
    el: document.getElementById("status_field"),
    setFail: function(message) {
        this.el.innerHTML = message;
        console.log(message);
    },
    
    setOk: function(message) {
        this.el.innerHTML = message;
    },
    
    clear: function() {
        this.el.innerHTML = '';
    }
};