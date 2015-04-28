var idSelectedProject;
var projects = {
    deleteProject: function(idProject) {

        sendRequest('project/delete', {id_project: idProject}, function(response){
            projects.reload();

        });
    },
    reload: function() {
        sendRequest('project/getall',{}, function(response){

            var out = '';

            for (var i in response.response.data) {
                var id = response.response.data[i].id_project;
                out += "<div class='project_block' id='project_block_"
                        + id
                        + "' OnClick='projects.selectProjectById(" + id + ")'>"
                        + response.response.data[i].name
                        + "<span OnClick='projects.deleteProject("
                        + id
                        + ")'>delete</span></div>";
            }

            var el = document.querySelectorAll('#projects_block');
                el[0].innerHTML = out;

            console.log(out);
        });
    },

    selectProjectById: function(idProject) {
        $('.project_block').removeClass('selected_project');
        $('#project_block_' + idProject).addClass('selected_project');
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