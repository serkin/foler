
var projects = {
    
    reload: function() {
        sendRequest('project/getall',{}, function(response){

            var out = '';

            for (var i in response.response) {
                out += response.response[i].name + "<br />";
            }

            var el = document.querySelectorAll('#projects_block');
                el[0].innerHTML = out;

            console.log(out);
        });
    },

    selectProjectById: function(idProject) {},
    export: function(idProject, type) {},
    
    
    ProjectForm: {
        save: function(){},
        clear: function() {},
        fillWithProjectData: function(idProject) {}
    }
};