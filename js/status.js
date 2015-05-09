
var statusField = {
    el: $('#status_field'),
    setFail: function(message) {
        this.el.html('<p class="bg-danger">'+message+'</p>');
        setTimeout(function() {
            statusField.clear();
        }, 5000);
    },
    
    setOk: function(message) {
        this.el.html('<p class="bg-success">'+message+'</p>');
        setTimeout(function() {
            statusField.clear();
        }, 5000);
    },
    
    clear: function() {
        this.el.html('');
    },
    
    render: function(status) {

        if(status.state === 'Ok') {
            this.setOk(status.message);
        }
        
        if(status.state === 'notOk') {
            this.setFail(status.message);
        }
    }
};