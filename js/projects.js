var idSelectedProject;
var projects = {
    deleteProject: function(idProject) {

        sendRequest('project/delete', {id_project: idProject}, function(response){
            projects.reload();

        });
    },
    reload: function() {
        sendRequest('project/getall',{}, function(response){
             
            var template = $('#projectsTemplate').html();
            var rendered = Mustache.render(template, response.response);

            $('#projects_block').html(rendered);
        });
    },

    selectProjectById: function(idProject) {
        $('.project_block').removeClass('success');
        $('#project_block_' + idProject).addClass('success');
        idSelectedProject = idProject;
    },
    export: function(idProject, type) {},
    
    
    ProjectForm: {
        save: function(){
            var form = document.getElementById('project_form');
            var data = serialize(form);

            sendRequest('project/save', data, function(response){
                projects.reload();
            });
        },
        clear: function() {},
        fillWithProjectData: function(idProject) {}
    }
};