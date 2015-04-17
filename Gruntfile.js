/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),
    // Task configuration.
    concat: {
        php: {
            options: {
                banner: '<?php',
                footer: '\n?>',
                process: function(src, filepath) {
                return '\n// Source: ' + filepath + '\n' +
                  src.replace("<?php","");
              }
            },
            files: {
              'build/php': ['config/header.php', 'classes/**/*.php', 'i18n/*.php', 'controllers/**/*.php', 'config/footer.php']
              }
          },
          js: {
            options: {
                banner: '<script>',
                footer: '\n</script>'
            },
            files: {
              'build/js': ['js/*.js']
              }
          },
          layout: {
              options: {
              process: function(src) {
                return src.replace("{ js }", grunt.file.read('build/js'));
            }},
            files: {
              'foler.php': ['build/php', 'layout/layout.html']
              }
          }
      }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-concat');

  // Default task.
  grunt.registerTask('default', ['concat:php', 'concat:js', 'concat:layout']);

};
