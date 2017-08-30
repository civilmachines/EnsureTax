var elixir = require('laravel-elixir');
require('laravel-elixir-ngtemplatecache');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    mix
        .ngTemplateCache('/*.html', 'resources/assets/js/templates', 'resources/assets/templates', {
            templateCache: {
                filename: 'defaultTemplates.js',
                module: 'defaultTemplates',
                standalone: true,
            },
            htmlmin: {
                collapseWhitespace: true,
                removeComments: true,
            },
        })
        .ngTemplateCache('/*.html', 'resources/assets/js/templates', 'resources/assets/templates/home', {
            templateCache: {
                filename: 'homeTemplates.js',
                module: 'homeTemplates',
                standalone: true
            },
            htmlmin: {
                collapseWhitespace: true,
                removeComments: true
            }
        })
        .ngTemplateCache('/*.html', 'resources/assets/js/templates', 'resources/assets/templates/dashboard', {
            templateCache: {
                filename: 'dashboardTemplates.js',
                module: 'dashboardTemplates',
                // standalone: true,
            },
            htmlmin: {
                collapseWhitespace: true,
                removeComments: true,
            }
        })
        .ngTemplateCache('/*.html', 'resources/assets/js/templates', 'resources/assets/templates/static', {
            templateCache: {
                filename: 'staticTemplates.js',
                module: 'staticTemplates',
                standalone: true,
            },
            htmlmin: {
                collapseWhitespace: true,
                removeComments: true,
            }
        })
        .styles([
            "vendor/bootstrap.min.css",
            "vendor/mdb.css",
            "vendor/angular-material.min.css",
            "vendor/angular-toastr.css",
            "vendor/style.css",
        ], 'public/css/vendor.min.css')
        .styles([
            "home.css",
            "dialog.css",
        ], 'public/css/home.min.css')
        .styles([
            "vendor/dropzone.css",
            "post_property.css",
            "dashboard.css",
            "animation.css",
        ], 'public/css/dashboard.min.css')
        .styles([
            "static_page.css",
        ], 'public/css/static.min.css')
        .scripts([
            "jquery/jquery-3.1.1.min.js",
            "jquery/tether.min.js",
            "jquery/bootstrap.min.js",
            "angular/vendor/angular.min.js",
            "angular/vendor/ui-bootstrap-paging-2.5.0.min.js",
            "angular/vendor/ui-bootstrap-modals-2.5.0.min.js",
            "angular/vendor/angular-animate.min.js",
            "angular/vendor/angular-aria.min.js",
            "angular/vendor/angular-messages.min.js",
            // "angular/vendor/angular-sticky.js",
            "angular/vendor/angular-material.min.js",
            "angular/vendor/angular-ui-router.min.js",
            "angular/vendor/angular-toastr.tpls.js",
            "angular/vendor/satellizer.min.js",
            "angular/vendor/angular-utf8-base64.min.js",
            "angular/vendor/moment.min.js",
            "templates/defaultTemplates.js",
            "angular/vendor/angular-local-storage.js",
            "angular/app.js",
            "angular/route.js",
            "angular/directives/appDirective.js",
            "angular/filter.js",
            "angular/controllers/appController.js",
            "angular/services/appService.js"
        ], 'public/js/vendor.min.js')
        .scripts([
            "templates/homeTemplates.js",
            "angular/controllers/homeController.js",
            "angular/services/homeService.js",
        ], 'public/js/home.min.js')
        .scripts([
            "jquery/dropzone.js",
            "templates/dashboardTemplates.js",
            "angular/directives/dashboardDirective.js",
            "angular/controllers/dashboardController.js",
            "angular/services/dashboardService.js",
        ], 'public/js/dashboard.min.js')

        .scripts([
            "templates/staticTemplates.js",
            "angular/controllers/staticController.js",
        ], 'public/js/static.min.js')


});