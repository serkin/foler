var idSelectedProject;
var projects = {
    deleteProject: function(idProject) {

        sendRequest('project/delete', {id_project: idProject}, function(response){
            projects.reload();

        });
    },
    reload: function() {
        sendRequest('project/getall',{}, function(response){

            var out = '<table class="table table-condensed"><thead><th>#</th><th>Name</th><th>Manage</th></thead><tbody>';

            for (var i in response.response.data) {
                var id = response.response.data[i].id_project;
                out += "<tr class='project_block' id='project_block_"
                        + id
                        + "' OnClick='projects.selectProjectById(" + id + ")'><td>" + id + "</td><td>"
                        + response.response.data[i].name
                        + "</td><td><button class='btn btn-danger btn-xs' OnClick='projects.deleteProject("
                        + id
                        + ")'>delete</button></td></tr>";
            }
            out += '</tbody></table>';

            var el = document.querySelectorAll('#projects_block');
                el[0].innerHTML = out;

            console.log(out);
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