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
            var rendered = Mustache.render(template, response);

            $('#projectsBlock').html(rendered);
        });

        projects.ProjectForm.render();
        codes.CodeForm.hide();
    },

    selectProjectById: function(idProject) {

            $('.project_block').removeClass('success');
            $('#project_block_' + idProject).addClass('success');
            idSelectedProject = idProject;
            projects.ProjectForm.render(idSelectedProject);
            
            codes.CodeForm.render();

    },
    export: function(idProject, type) {},
    
    
    ProjectForm: {
        save: function(){
            var data = $('#projectForm').serialize();

            sendRequest('project/save', data, function(response){
                projects.reload();
            });
        },
        clear: function() {},
        render: function(idProject) {

            var template = $('#projectFormTemplate').html();

            if(idProject === undefined) {

                var rendered = Mustache.render(template);
                $('#projectFormBlock').html(rendered);

            } else {

                sendRequest('project/getone',{id_project:idProject}, function(response){

                    var rendered = Mustache.render(template, response.project);
                    $('#projectFormBlock').html(rendered);
                });
        }
            
        }
    }
};