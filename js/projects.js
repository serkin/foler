var idSelectedProject;
var projects = {
    deleteProject: function(idProject) {

        sendRequest('project/delete', {id_project: idProject}, function(response){
            statusField.render(response.status);
            translation.render();
            projects.reload();
            idSelectedProject = null;

        });
    },
    reload: function() {
        sendRequest('project/getall',{}, function(response){

            response.data.i18n = i18n;
            var template = $('#projectsTemplate').html();
            var rendered = Mustache.render(template, response.data);

            $('#projectsBlock').html(rendered);
        });

        projects.ProjectForm.render();
    },

    selectProjectById: function(idProject) {

        $('#idGlobalProject').val(idProject);
        $('.project_block').removeClass('success');
        $('#project_block_' + idProject).addClass('success');
        idSelectedProject = parseInt(idProject);
        projects.ProjectForm.render(idSelectedProject);

        translation.render();
        codes.SearchField.show();

    },
    export: function(idProject, type, ev) {
        sendRequest('project/export', {id_project: idProject, type: type}, function(response){
                statusField.render(response.status);
        });
        ev.stopPropagation();
    },


    ProjectForm: {
        save: function(){
            var data = $('#projectForm').serialize();

            sendRequest('project/save', {form: data}, function(response){

                statusField.render(response.status);

                if(response.status.state === 'Ok'){
                    projects.reload();

                    var id = parseInt(response.data.id_project);

                    if(id > 0){
                        projects.selectProjectById(id);
                    }
                }

            });
        },

        render: function(idProject) {

            var template = $('#projectFormTemplate').html();

            if(idProject === undefined) {

                var rendered = Mustache.render(template);
                $('#projectFormBlock').html(rendered);

            } else {

                sendRequest('project/getone',{id_project:idProject}, function(response){

                    var rendered = Mustache.render(template, response.data.project);
                    $('#projectFormBlock').html(rendered);
                });
            }
        }
    }
};
