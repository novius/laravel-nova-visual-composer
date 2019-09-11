let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .sass('resources/sass/field.scss', 'css')
    .styles([
      'node_modules/filepond/dist/filepond.css',
      'node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css',
    ], 'dist/css/filepond.css');
