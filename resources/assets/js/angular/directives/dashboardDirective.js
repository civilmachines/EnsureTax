/**
 * Created by Admin on 5/26/2017.
 */
app
/* .directive('hscp', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
 return {
 restrict: 'E', //This menas that it will be used as an attribute and NOT as an element. I don't like creating custom HTML elements
 replace: false,
 templateUrl: function () {
 if ($authModel.viewPermission($CONFIG.$VIEWS_PERMISSION.CHANNEL_PARTNER))
 return "channel-partner.html";
 else
 return "empty.html";
 },
 }
 }])*/
/*.directive('hsOwner', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
 return {
 restrict: 'E', //This menas that it will be used as an attribute and NOT as an element. I don't like creating custom HTML elements
 replace: true,
 template: function (element, attrs) {
 var html = '<p></p>';
 if ($authModel.viewPermission($CONFIG.$VIEWS_PERMISSION.CHANNEL_PARTNER)) {
 html = '<p class="pull-right"><span class="owner_name" data-ng-if="row.owner_name"><i class="fa fa-user-md"></i> '
 html += '<span data-ng-bind="row.owner_name"></span><md-tooltip md-direction="top">Owner</md-tooltip></span></p>';
 }
 return html
 },
 }
 }])*/
/* .directive('hsDealAdd', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
 return {
 restrict: 'E', //This menas that it will be used as an attribute and NOT as an element. I don't like creating custom HTML elements
 replace: true,
 template: function (element, attrs) {
 var html = '<div></div>';
 if ($authModel.viewPermission($CONFIG.$VIEWS_PERMISSION.ADD_DEAL)) {
 html = '<button class="add_property_btn" aria-label="Add Offers" data-ng-click="addDeal();">'
 html += '<i class="fa fa-plus"></i><md-tooltip md-direction="top">Add Offers</md-tooltip></button>';
 }
 return html
 },
 }
 }])*/
/*.directive('hsDealAction', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
 return {
 restrict: 'E', //This menas that it will be used as an attribute and NOT as an element. I don't like creating custom HTML elements
 replace: true,
 template: function (element, attrs) {
 var cls = false;
 var html = '';
 if ($authModel.viewPermission($CONFIG.$VIEWS_PERMISSION.EDIT_DEAL))
 cls = true, html += '<a data-ng-click="edit(row)" class="teal-text"><i class="fa fa-pencil"></i><md-tooltip md-direction="top"> Edit </md-tooltip></a>';
 if ($authModel.viewPermission($CONFIG.$VIEWS_PERMISSION.PUBLISH_DEAL))
 cls = true, html += '&nbsp;&nbsp;&nbsp; <a class="<%row.is_active>0?\'green-text\':\'red-text\'%>" data-ng-click="publish(row)"><i class="fa <%row.is_active>0?\'fa-check\':\'fa-times\'%>"></i> <md-tooltip md-direction="top"><%row.is_active>0?\'Published\':\'UnPublished\'%>  Created By: <%row.created_by.name%></md-tooltip></a>';
 if ($authModel.viewPermission($CONFIG.$VIEWS_PERMISSION.APPROVE_DEAL))
 cls = true, html += '&nbsp;&nbsp;&nbsp; <a class="<%row.is_approved>0?\'blue-text\':\'orange-text\'%>" data-ng-click="approve(row)" class="teal-text"><i class="fa <%row.is_approved>0?\'fa-arrow-circle-up\':\'fa-arrow-circle-down\'%>"></i><md-tooltip md-direction="top"><%row.is_approved>0?\'Approved,\':\'Pending,\'%> Approved By: <%row.approved_by.name%> </md-tooltip></a>';
 return cls ? '<span class="btn btn-outline-info btn-rounded">' + html + '</span>' : '<span></span>'
 },
 }
 }])*/
    .directive('itrMode', function () {
        return {
            restrict: 'E',
            replace: true,
            link: function (scope, element, attrs) {
                scope.$watch('itr.ITR', function (newValue, oldValue) {
                    if (newValue && newValue > 2) {
                        scope.getContentUrl = function () {
                            return 'itr_' + parseInt(newValue) + '.html';
                        };
                    } else {
                        scope.getContentUrl = function () {
                            return 'empty.html';
                        }
                    }
                });
            },
            template: '<div ng-include="getContentUrl()"></div>'
        }
    })
    .directive('saveBtn', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
        return {
            restrict: 'E',
            replace: true,
            template: function (element, attrs) {
                var html =
                    '<button class="btn btn-rounded btn-lg waves-effect waves-light mt-1"' +
                    'data-ng-disabled="activated">Save' +
                    '</button>';
                return !$authModel.viewPermission($CONFIG.$ROLES.ADMIN) ? html : '<span></span>'
            }
        }
    }])

    .directive('nextBtn', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
        return {
            restrict: 'E',
            replace: true,
            template: function (element, attrs) {
                var html =
                    '<a class="btn btn-rounded btn-lg waves-effect waves-light mt-1"' +
                    'data-ng-click="nextTab()">Next </a>';
                return $authModel.viewPermission($CONFIG.$ROLES.ADMIN) ? html : '<span></span>'

            }
        }
    }])
    .directive('uploadBtn', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
        return {
            restrict: 'E',
            replace: true,
            template: function (element, attrs) {
                var html =
                    '<div class="col-md-4 text-center">' +
                    '<label class="dp-upload">' +
                    '<input type="file" ng-file-model="' + attrs.name + '" multiple>' +
                    '<i class="fa fa-upload"></i> <span>Upload doc</span>' +
                    '</label></div>';
                return !$authModel.viewPermission($CONFIG.$ROLES.ADMIN) ? html : '<span></span>'
            }
        }
    }])
    .directive('delImage', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
        return {
            restrict: 'E',
            replace: true,
            template: function (element, attrs) {
                var html =
                    '<a class="remove_img"><i class="fa fa-close"></i></a>';
                return !$authModel.viewPermission($CONFIG.$ROLES.ADMIN) ? html : '<span></span>'
            }
        }
    }])
    .directive('sortBy', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
        return {
            restrict: 'E',
            replace: true,
            transclude: true,
            template: function (element, attrs) {
                var html =
                    '<div class="col-md-2 text-right" data-ng-show="directives" ng-transclude>' +
                    '</div>';
                return $authModel.viewPermission($CONFIG.$ROLES.ADMIN) ? html : '<span></span>'
            }
        }
    }])
    .directive('itrStatus', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
        return {
            restrict: 'E',
            replace: true,
            template: function (element, attrs) {
                var html =
                    '<div class="col-md-4" data-ng-show="directives">' +
                    '<md-input-container class="md-block"><label>Status</label>' +
                    '<md-select name="status" data-ng-disabled="disabled" data-ng-change="changeStatus()" ng-model="itr.status">' +
                    '<md-option ng-repeat="sta in status track by $index" ng-value="<%sta.value%>">' +
                    '<%sta.text%></md-option>' +
                    '<md-option ng-value="0">All' +
                    '</md-option></md-select></md-input-container></div>';
                return $authModel.viewPermission($CONFIG.$ROLES.ADMIN) ? html : '<span></span>'
            }
        }
    }])
    .directive('addCorpItr', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
        return {
            restrict: 'E',
            replace: true,
            template: function (element, attrs) {
                var html =
                    '<div class="row" data-ng-if="itr_exists">' +
                    '<div class="col-sm-12 col-md-3 text-right">' +
                    '<a ui-sref="add-corp-itr" class="btn btn-primary">' +
                    'ADD ITR</a>' +
                    '</div></div>'
                return !$authModel.viewPermission($CONFIG.$ROLES.ADMIN) ? html : '<span></span>'
            }
        }
    }])

    .directive('itrCheck', ['$authModel', '$CONFIG', function ($authModel, $CONFIG) {
        return {
            restrict: 'E',
            replace: true,
            template: function (element, attrs) {
                var html =
                    '<fieldset class="form-group">' +
                    '<input type="checkbox" ng-checked="exists(row.id, itr.selected)" ng-click="toggle(row.id, itr.selected)" name="checkbox" ng-value="row.id" id="checkbox-<%$index%>">' +
                    '<label for="checkbox-<%$index%>"></label>' +
                    '</fieldset>';
                return $authModel.viewPermission($CONFIG.$ROLES.ADMIN) ? html : '<span></span>'
            }
        }
    }])
    .directive('fileModel', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var model = $parse(attrs.fileModel);
                var modelSetter = model.assign;
                element.bind('change', function () {
                    scope.$apply(function () {
                        modelSetter(scope, element[0].files);
                    });
                });
            }
        };
    }])

    .directive('ngFileModel', ['$parse', '$CONFIG', function ($parse, $CONFIG) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var model = $parse(attrs.ngFileModel);
                var isMultiple = attrs.multiple;
                var modelSetter = model.assign;
                var values = [];
                element.bind('change', function () {
                    angular.forEach(element[0].files, function (item) {
                        var value = {
                            // File Name
                            name: item.name,
                            //File Size
                            size: item.size,
                            //File URL to view
                            url: item.type == 'application/pdf' ? $CONFIG.PDF_DEFAULT_IMAGE : URL.createObjectURL(item),
                            // File Input Value
                            _file: item
                        };
                        values.push(value);
                    });
                    scope.$apply(function () {
                        if (isMultiple) {
                            modelSetter(scope, values);
                        } else {
                            modelSetter(scope, values[0]);
                        }
                    });
                });
            }
        };
    }])

    .directive('ngDropzone', ['$timeout', 'dropzoneOps', function ($timeout, dropzoneOps) {
        return {
            restrict: 'AE',
            template: '<div></div>',
            replace: true,
            scope: {
                options: '=?', //http://www.dropzonejs.com/#configuration-options
                callbacks: '=?', //http://www.dropzonejs.com/#events
                methods: '=?' //http://www.dropzonejs.com/#dropzone-methods
            },
            link: function (scope, iElem, iAttr) {
                //Set options for dropzone {override from dropzone options provider}
                scope.options = scope.options || {};

                var initOps = angular.extend({}, dropzoneOps, scope.options);


                //Instantiate dropzone with initOps
                var dropzone = new Dropzone(iElem[0], initOps);


                /*********************************************/


                //Instantiate Dropzone methods (Control actions)
                scope.methods = scope.methods || {};
                scope.methods.getDropzone = function () {
                    return dropzone; //Return dropzone instance
                };

                scope.methods.getAllFiles = function () {
                    return dropzone.files; //Return all files
                };

                var controlMethods = [
                    'removeFile', 'removeAllFiles', 'processQueue',
                    'getAcceptedFiles', 'getRejectedFiles', 'getQueuedFiles', 'getUploadingFiles',
                    'disable', 'enable', 'confirm', 'createThumbnailFromUrl'
                ];

                angular.forEach(controlMethods, function (methodName) {
                    scope.methods[methodName] = function () {
                        dropzone[methodName].apply(dropzone, arguments);
                        if (!scope.$$phase && !scope.$root.$$phase)
                            scope.$apply();
                    }
                });


                /*********************************************/


                //Set invents (callbacks)
                if (scope.callbacks) {
                    var callbackMethods = [
                        'drop', 'dragstart', 'dragend',
                        'dragenter', 'dragover', 'dragleave', 'addedfile', 'removedfile',
                        'thumbnail', 'error', 'processing', 'uploadprogress',
                        'sending', 'success', 'complete', 'canceled', 'maxfilesreached',
                        'maxfilesexceeded', 'processingmultiple', 'sendingmultiple', 'successmultiple',
                        'completemultiple', 'canceledmultiple', 'totaluploadprogress', 'reset', 'queuecomplete'
                    ];
                    angular.forEach(callbackMethods, function (method) {
                        var callback = (scope.callbacks[method] || angular.noop);
                        dropzone.on(method, function () {
                            callback.apply(null, arguments);
                            if (!scope.$$phase && !scope.$root.$$phase)
                                scope.$apply();
                        });
                    });
                }
            }
        }
    }])
    .provider('dropzoneOps', function () {
        /*
         *	Add default options here
         **/
        var defOps = {
            //Add your options here
        };

        return {
            setOptions: function (newOps) {
                angular.extend(defOps, newOps);
            },
            $get: function () {
                return defOps;
            }
        }
    })