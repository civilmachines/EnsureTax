angular.module("defaultTemplates", []).run(["$templateCache", function($templateCache) {$templateCache.put("authentication.html","<div data-ng-class=\"active\">\r\n\r\n    <div class=\"login-content\">\r\n        <div class=\"modal-header\">\r\n            <button type=\"button\" class=\"close\" data-ng-click=\"close()\"><span\r\n                    aria-hidden=\"true\">&times;</span></button>\r\n            <h3 class=\"w-100\">Login</h3>\r\n        </div>\r\n\r\n        <form name=\"loginForm\" novalidate data-ng-submit=\"login()\">\r\n            <div class=\"modal-body pb-0\">\r\n\r\n                <md-input-container class=\"md-block\">\r\n                    <label>Your email / mobile</label>\r\n                    <input name=\"email\" data-ng-model=\"user.email\" type=\"text\"\r\n                           data-ng-pattern=\"/^([a-zA-Z0-9._]+@[a-zA-Z0-9]+\\.[a-zA-Z.]{2,5}|\\+?\\d[0-9-]{9,14})$/\"\r\n                           data-ng-exist=\"{property: \'email\'}\" required/>\r\n                    <div class=\"tooltip-error\">\r\n                        <div ng-messages=\"loginForm.email.$error\">\r\n                            <span ng-message=\"required\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Please enter your mobile or email address.\r\n                                </md-tooltip>\r\n                            </span>\r\n                            <span ng-message=\"pattern\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Please enter valid mobile or email address.\r\n                                </md-tooltip>\r\n                            </span>\r\n                            <span ng-message=\"exist\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    You are not registered user!\r\n                                </md-tooltip>\r\n                            </span>\r\n                        </div>\r\n                    </div>\r\n                </md-input-container>\r\n\r\n                <md-input-container class=\"md-block\">\r\n                    <label>Password</label>\r\n                    <input id=\"password\" name=\"password\" data-ng-model=\"user.password\" type=\"password\" required/>\r\n                    <div class=\"tooltip-error\">\r\n                        <div ng-messages=\"loginForm.email.$error\">\r\n                            <span ng-message=\"required\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Please enter your password.\r\n                                </md-tooltip>\r\n                            </span>\r\n                        </div>\r\n                    </div>\r\n                </md-input-container>\r\n\r\n                <div class=\"row\">\r\n                    <div class=\"col-sm-12\">\r\n                        <p class=\"pull-left login_text\">Not a member? <a href=\"#\" data-ng-click=\"alink(\'register\')\">Sign\r\n                            Up</a></p>\r\n\r\n                        <p class=\"pull-right text-right small forgot_text back_it1\"> Forgot <a href=\"#\"\r\n                                                                                               data-ng-click=\"alink(\'forgot\')\">\r\n                            Password?</a></p>\r\n\r\n                    </div>\r\n                </div>\r\n            </div>\r\n            <div class=\"modal-footer text-right\">\r\n                <button class=\"btn btn-lg btn-success btn-block ml-auto\" data-ng-disabled=\"activated\">Login</button>\r\n                <md-progress-linear data-ng-if=\"activated\" class=\"md-warn\" md-mode=\"buffer\"\r\n                                    value=\"<% determinateValue %>\"\r\n                                    md-buffer-value=\"<% determinateValue2 %>\"\r\n                                    data-ng-disable=\"activated\"></md-progress-linear>\r\n            </div>\r\n        </form>\r\n\r\n    </div>\r\n\r\n    <div class=\"forgot-content\">\r\n        <div class=\"modal-header\">\r\n            <button type=\"button\" class=\"back back_it1\" data-ng-click=\"alink()\">\r\n                <span aria-hidden=\"true\">&larr;</span></button>\r\n            <h3 class=\"w-100\">Login</h3>\r\n        </div>\r\n        <form name=\"otpLoginForm\" novalidate data-ng-submit=\"otpLogin()\">\r\n            <div class=\"modal-body pb-0\">\r\n                <md-input-container class=\"md-block\">\r\n                    <label>Your email / mobile</label>\r\n                    <input id=\"email\" name=\"email\" data-ng-model=\"user.email\" type=\"text\"\r\n                           data-ng-pattern=\"/^([a-zA-Z0-9._]+@[a-zA-Z0-9]+\\.[a-zA-Z.]{2,5}|\\+?\\d[0-9-]{9,14})$/\"\r\n                           data-ng-exist=\"{property: \'email\'}\" required/>\r\n                    <div class=\"tooltip-error\">\r\n                        <div ng-messages=\"otpLoginForm.email.$error\">\r\n                            <span ng-message=\"required\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Please enter your mobile or email address.\r\n                                </md-tooltip>\r\n                            </span>\r\n                            <span ng-message=\"pattern\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Please enter valid mobile or email address.\r\n                                </md-tooltip>\r\n                            </span>\r\n                            <span ng-message=\"exist\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    You are not registered user!\r\n                                </md-tooltip>\r\n                            </span>\r\n                        </div>\r\n                    </div>\r\n                </md-input-container>\r\n                <!--<div class=\"row\">-->\r\n                <!--<div class=\"col-sm-12\">-->\r\n                <!--<p class=\"pull-right small forgot_text\"> Have-->\r\n                <!--<a href=\"#\" data-ng-click=\"alink()\">Password?</a>-->\r\n                <!--</p>-->\r\n                <!--</div>-->\r\n                <!--</div>-->\r\n            </div>\r\n            <div class=\"modal-footer text-right\">\r\n                <button class=\"btn btn-lg btn-success btn-block ml-auto\" data-ng-disabled=\"activated\">Submit</button>\r\n                <md-progress-linear data-ng-if=\"activated\" class=\"md-warn\" md-mode=\"buffer\"\r\n                                    value=\"<% determinateValue %>\"\r\n                                    md-buffer-value=\"<% determinateValue2 %>\"\r\n                                    data-ng-disable=\"activated\"></md-progress-linear>\r\n            </div>\r\n        </form>\r\n    </div>\r\n\r\n    <div class=\"otp-content\">\r\n        <div class=\"modal-header\">\r\n            <button type=\"button\" class=\"back\" data-ng-click=\"alink()\"><span aria-hidden=\"true\">&larr;</span>\r\n            </button>\r\n            <h3 class=\"w-100\">Login</h3>\r\n        </div>\r\n        <form name=\"otpForm\" novalidate data-ng-submit=\"verify()\">\r\n            <div class=\"modal-body pb-0\">\r\n                <md-input-container class=\"md-block\">\r\n                    <label>Your email / mobile</label>\r\n                    <input name=\"email\" data-ng-model=\"user.email\" type=\"text\"\r\n                           data-ng-pattern=\"/^([a-zA-Z0-9._]+@[a-zA-Z0-9]+\\.[a-zA-Z.]{2,5}|\\+?\\d[0-9-]{9,14})$/\"\r\n                           data-ng-exist=\"{property: \'email\'}\" required disabled/>\r\n                    <div class=\"tooltip-error\">\r\n                        <div ng-messages=\"otpForm.email.$error\">\r\n                            <span ng-message=\"required\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Please enter your mobile or email address.\r\n                                </md-tooltip>\r\n                            </span>\r\n                            <span ng-message=\"pattern\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Please enter valid mobile or email address.\r\n                                </md-tooltip>\r\n                            </span>\r\n                            <span ng-message=\"exist\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    You are not registered user!\r\n                                </md-tooltip>\r\n                            </span>\r\n                        </div>\r\n                    </div>\r\n                    <span class=\"change\"><a href=\"#\" data-ng-click=\"alink(\'forgot\')\"> Change  <i\r\n                            class=\"fa fa-repeat\"></i></a></span>\r\n                </md-input-container>\r\n\r\n                <div class=\"col-md-6 center-block border-top-0\">\r\n                    <md-input-container class=\"md-block btn-rounded\">\r\n                        <label>Enter OTP</label>\r\n                        <input name=\"otp\" data-ng-model=\"user.otp\" type=\"text\" data-ng-pattern=\"/^[0-9]{1,6}$/\"\r\n                               data-ng-valid required/>\r\n                        <div class=\"tooltip-error\">\r\n                            <div ng-messages=\"otpForm.otp.$error\">\r\n                                <span ng-message=\"required\">\r\n                                    <i class=\"fa fa-info-circle\"></i>\r\n                                    <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                        Please enter your mobile or email address.\r\n                                    </md-tooltip>\r\n                                </span>\r\n                                <span ng-message=\"pattern\">\r\n                                    <i class=\"fa fa-info-circle\"></i>\r\n                                    <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                        Please enter valid OTP.\r\n                                    </md-tooltip>\r\n                                </span>\r\n                                <span ng-message=\"valid\">\r\n                                    <i class=\"fa fa-info-circle\"></i>\r\n                                    <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                        Invalid OTP\r\n                                    </md-tooltip>\r\n                                </span>\r\n                            </div>\r\n                        </div>\r\n                    </md-input-container>\r\n                    <p class=\"small\"><a href=\"#\" data-ng-click=\"resent()\">Resend OTP <i class=\"fa fa-repeat\"></i></a>\r\n                    </p>\r\n                </div>\r\n            </div>\r\n\r\n            <div class=\"modal-footer text-right\">\r\n                <button class=\"btn btn-lg btn-success btn-block ml-auto\" data-ng-disabled=\"activated\">Verify</button>\r\n                <md-progress-linear data-ng-if=\"activated\" class=\"md-warn\" md-mode=\"buffer\"\r\n                                    value=\"<% determinateValue %>\"\r\n                                    md-buffer-value=\"<% determinateValue2 %>\"\r\n                                    data-ng-disable=\"activated\"></md-progress-linear>\r\n            </div>\r\n        </form>\r\n    </div>\r\n\r\n    <div class=\"register-content\">\r\n        <div class=\"modal-header\">\r\n            <button type=\"button\" class=\"back back_it1\" data-ng-click=\"alink()\">\r\n                <span aria-hidden=\"true\">&larr;</span></button>\r\n            <h3 class=\"w-100\">Sign Up</h3>\r\n        </div>\r\n\r\n        <form name=\"registerForm\" novalidate data-ng-submit=\"register()\">\r\n            <div class=\"modal-body pb-0\">\r\n                <!--<div class=\"row social_login mb-2\">\r\n                  <div class=\"col-sm-6 text-right\">\r\n                       <button type=\"button\" class=\"btn btn-fb btn-block waves-effect waves-light\" tabindex=\"59\"><i class=\"fa fa-facebook left\"></i> Facebook</button>\r\n                   </div>\r\n                   <div class=\"col-sm-6 text-left\">\r\n                       <button type=\"button\" class=\"btn btn-gplus btn-block waves-effect waves-light\" tabindex=\"61\"><i class=\"fa fa-google-plus left\"></i> Google +</button>\r\n                   </div>\r\n\r\n               </div>-->\r\n\r\n\r\n                <md-input-container class=\"md-block\">\r\n                    <label>Name</label>\r\n                    <input name=\"name\" data-ng-model=\"user.name\" type=\"text\" ng-pattern=\"/^[a-zA-Z\\s]*$/\"\r\n                           required/>\r\n                    <div class=\"tooltip-error\">\r\n                        <div ng-messages=\"registerForm.name.$error\">\r\n                                        <span ng-message=\"required\">\r\n                                           <i class=\"fa fa-info-circle\"></i>\r\n                                               <md-tooltip md-direction=\"left\" md-z-index=\"99991\">\r\n                                                 Please enter your name.\r\n                                               </md-tooltip>\r\n                                        </span>\r\n                            <span ng-message=\"pattern\">\r\n                                           <i class=\"fa fa-info-circle\"></i>\r\n                                               <md-tooltip md-direction=\"left\" md-z-index=\"99991\">\r\n                                                   Please enter a valid name(alphabets only).\r\n                                               </md-tooltip>\r\n                                        </span>\r\n                        </div>\r\n                    </div>\r\n                </md-input-container>\r\n\r\n                <md-input-container class=\"md-block\">\r\n                    <label>Email</label>\r\n                    <input name=\"mail\" data-ng-model=\"user.email\" type=\"text\"\r\n                           data-ng-pattern=\"/^([a-zA-Z0-9._]+@[a-zA-Z0-9]+\\.[a-zA-Z.]{2,5}|\\+?\\d[0-9-]{9,14})$/\"\r\n                           ng-unique=\"{property: \'email\'}\" required/>\r\n                    <div class=\"tooltip-error\">\r\n                        <div ng-messages=\"registerForm.mail.$error\">\r\n                            <span ng-message=\"required\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Please enter email address.\r\n                                </md-tooltip>\r\n                            </span>\r\n                            <span ng-message=\"pattern\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Please enter valid email address.\r\n                                </md-tooltip>\r\n                            </span>\r\n                            <span ng-message=\"unique\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"9990\">\r\n                                    Email-ID already in use!\r\n                                </md-tooltip>\r\n                            </span>\r\n                        </div>\r\n                    </div>\r\n                </md-input-container>\r\n\r\n                <md-input-container class=\"md-block\">\r\n                    <label>Mobile</label>\r\n                    <input name=\"username\" data-ng-model=\"user.username\" type=\"text\"\r\n                           data-ng-pattern=\"/^\\+?\\d[0-9-]{9,20}/\"\r\n                    /> <!--required data-ng-unique=\"{property: \'username\'}\"-->\r\n                    <div class=\"tooltip-error\">\r\n                        <div ng-messages=\"registerForm.username.$error\">\r\n                            <!--<span ng-message=\"required\">-->\r\n                            <!--<i class=\"fa fa-info-circle\"></i>-->\r\n                            <!--<md-tooltip md-direction=\"left\" md-z-index=\"99991\">-->\r\n                            <!--Please Enter Valid Mobile Number-->\r\n                            <!--</md-tooltip>-->\r\n                            <!--</span>-->\r\n                            <span ng-message=\"pattern\">\r\n                                <i class=\"fa fa-info-circle\"></i>\r\n                                <md-tooltip md-direction=\"left\" md-z-index=\"99991\">\r\n                                    Please Enter a Valid Mobile Number\r\n                                </md-tooltip>\r\n                            </span>\r\n                            <!--<span ng-message=\"unique\">-->\r\n                            <!--<i class=\"fa fa-info-circle\"></i>-->\r\n                            <!--<md-tooltip md-direction=\"left\" md-z-index=\"99991\">-->\r\n                            <!--Mobile Number is Already in Use-->\r\n                            <!--</md-tooltip>-->\r\n                            <!--</span>-->\r\n                        </div>\r\n                    </div>\r\n                </md-input-container>\r\n                <md-input-container class=\"md-block\">\r\n                    <label>Password</label>\r\n                    <input name=\"password\" data-ng-model=\"user.password\" type=\"password\"\r\n                           data-ng-minlength=\"6\" data-ng-maxlength=\"20\" required/>\r\n                    <div class=\"tooltip-error\">\r\n                        <div ng-messages=\"registerForm.password.$error\">\r\n                                        <span ng-message=\"required\">\r\n                                           <i class=\"fa fa-info-circle\"></i>\r\n                                               <md-tooltip md-direction=\"left\" md-z-index=\"99991\">\r\n                                                    Please enter your password.\r\n                                               </md-tooltip>\r\n                                        </span>\r\n                            <span ng-message=\"minlength\">\r\n                                           <i class=\"fa fa-info-circle\"></i>\r\n                                               <md-tooltip md-direction=\"left\" md-z-index=\"99991\">\r\n                                                     Enter minimum 6 character password\r\n                                               </md-tooltip>\r\n                                        </span>\r\n                            <span ng-message=\"maxlength\">\r\n                                           <i class=\"fa fa-info-circle\"></i>\r\n                                               <md-tooltip md-direction=\"left\" md-z-index=\"99991\">\r\n                                                     Password, Maximum 20 Characters\r\n                                               </md-tooltip>\r\n                                        </span>\r\n                        </div>\r\n                    </div>\r\n                </md-input-container>\r\n\r\n                <div class=\"row\">\r\n                    <div class=\"col-sm-12\">\r\n                        <p class=\"pull-left login_text\">Already a member? <a href=\"#\" data-ng-click=\"alink()\">Sign\r\n                            in</a></p>\r\n\r\n                    </div>\r\n                </div>\r\n            </div>\r\n            <div class=\"modal-footer text-right\">\r\n                <button class=\"btn btn-lg btn-success btn-block ml-auto\" data-ng-disabled=\"activated\">Sign Up</button>\r\n                <md-progress-linear data-ng-if=\"activated\" class=\"md-warn\" md-mode=\"buffer\"\r\n                                    value=\"<% determinateValue %>\"\r\n                                    md-buffer-value=\"<% determinateValue2 %>\"\r\n                                    data-ng-disable=\"activated\"></md-progress-linear>\r\n            </div>\r\n        </form>\r\n    </div>\r\n\r\n    <div class=\"clearfix\"></div>\r\n</div>");
$templateCache.put("footer.html","<footer class=\"page-footer mt-0 center-on-small-only\">\r\n    <div class=\"container\">\r\n        <div class=\"row\">\r\n            <div class=\"col-md-4\">\r\n                <h5 class=\"title\">About EnsureTax</h5>\r\n                <p>EnsureTax is a complete tax solution platform for all Indian tax needs with a team of more than100 professionals. Through EnsureTax, you can go... \r\n                    <a href=\"/about\" target=\"_self\" class=\"read\">Read more</a></p>\r\n            </div>\r\n\r\n\r\n             \r\n            <div class=\"col-md-2 col-md-offset-1\">\r\n                <h5 class=\"title\">Company</h5>\r\n                <ul>\r\n                    <li><a href=\"/\" target=\"_self\" title=\"Home\">Home</a></li>\r\n                    <li><a href=\"/about\" target=\"_self\" title=\"About\">About</a></li>\r\n                   <!-- <li><a href=\"/services\" target=\"_self\" title=\"Services\">Services</a></li> -->\r\n                    <li><a href=\"/contact\" target=\"_self\" title=\"Contact us\">Contact us</a></li>\r\n                </ul>\r\n            </div>\r\n\r\n            \r\n            <div class=\"col-md-2\">\r\n                <h5 class=\"title\">Support</h5>\r\n                <ul>\r\n                    <li><a href=\"/terms\" target=\"_self\" title=\"Terms of use\">Terms of use</a></li>\r\n                    <li><a href=\"/terms\" target=\"_self\" title=\"Privacy Policy\">Privacy Policy</a></li>\r\n                    <li><a href=\"javascript:;\" title=\"FAQs\">FAQs</a></li> \r\n\r\n                </ul>\r\n            </div>\r\n            \r\n             <div class=\"col-md-3\">\r\n                <h5 class=\"title\">Get in touch</h5>\r\n               <ul>\r\n               <li><i class=\"fa fa-map-marker\"></i> &nbsp; Plot no. 35, Arjun Marg, <br> &nbsp; &nbsp; &nbsp; DLF phase-1, Gurgaon -122002</li>\r\n               <li><i class=\"fa fa-phone\"></i> &nbsp;(+91) 995858 2028 </li>\r\n               <li><i class=\"fa fa-envelope\"></i> &nbsp;info@ensuretax.com</li>\r\n               </ul>\r\n            </div>\r\n            \r\n        </div>\r\n    </div>\r\n \r\n   \r\n    <div class=\"footer-copyright mt-2\">\r\n        <div class=\"container\">\r\n            <div class=\"row\">\r\n                <div class=\"col-md-8 text-left\">\r\n                    © 2017 <a href=\"javascript:;\"> EnsureTax.com\r\n                <span class=\"hidden-sm-down\"> All rights reserved.</span></a>\r\n                </div>\r\n                <div class=\"col-md-4 text-right\">\r\n                    <a href=\"#\"><i class=\"fa fa-facebook\"></i></a>\r\n                    <a href=\"#\"><i class=\"fa fa-twitter\"></i></a>\r\n                    <a href=\"#\"><i class=\"fa fa-linkedin\"></i></a>\r\n                    <a href=\"#\"><i class=\"fa fa-google-plus\"></i></a>\r\n                </div>\r\n            </div>\r\n            \r\n            \r\n            \r\n\r\n        </div>\r\n    </div>\r\n</footer>");
$templateCache.put("header.html","<header>\r\n    <md-sidenav class=\"md-sidenav-right\" md-component-id=\"right\" md-whiteframe=\"4\">\r\n        <div class=\"help_no\">\r\n            <!--<i class=\"fa fa-phone\"></i> +91 120 4545647-->\r\n        </div>\r\n        <md-content layout-margin>\r\n            <ul class=\"collapsible collapsible-accordion\">\r\n                <li><a href=\"/\" target=\"_self\"> Home</a></li>\r\n                <li><a href=\"/about\" target=\"_self\" title=\"About Us\">About Us</a></li>\r\n                <li><a href=\"/terms\" target=\"_self\" title=\"Terms of use\">Terms of use</a></li>\r\n                <li><a href=\"/terms\" target=\"_self\" title=\"Privacy Policy\">Privacy Policy</a></li>\r\n                <li><a href=\"/contact\" target=\"_self\" title=\"Contact us\">Contact us</a></li>\r\n            </ul>\r\n        </md-content>\r\n        <ul class=\"social\">\r\n            <li><a href=\"https://www.facebook.com\" target=\"_blank\" class=\"fb-ic\" title=\"facebook\"><i\r\n                    class=\"fa fa-facebook\"> </i></a></li>\r\n            <li><a class=\"pin-ic\"><i class=\"fa fa-pinterest\"> </i></a></li>\r\n            <li><a class=\"gplus-ic\"><i class=\"fa fa-google-plus\"> </i></a></li>\r\n            <li><a class=\"tw-ic\"><i class=\"fa fa-twitter\"> </i></a></li>\r\n        </ul>\r\n    </md-sidenav>\r\n    <nav class=\"navbar fixed-top navbar-toggleable-md navbar-dark double-nav\" scroll\r\n         ng-class=\"{scroll_fixed:nav_fixed}\">\r\n        <a href=\"/\" class=\"\" target=\"_self\"><img src=\"images/logo.png\" class=\"img-fluid\" alt=\"EnsureTax\"></a>\r\n\r\n        <ul class=\"nav navbar-nav nav-flex-icons ml-auto\">\r\n           \r\n\r\n            <li data-ng-if=\"!auth\" class=\"nav-item\">\r\n                <a href=\"#\" class=\"nav-link waves-effect waves-light\" data-ng-click=\"signin()\">\r\n                    <i class=\"fa fa-sign-in\"></i> Login</a>\r\n            </li>\r\n            <li data-ng-if=\"auth\" class=\"nav-item dropdown\">\r\n                <a class=\"nav-link dropdown-toggle waves-effect waves-light\" href=\"#\" id=\"userDropdown\"\r\n                   data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">\r\n                    <i class=\"fa fa-user\"></i> <span class=\"hidden-sm-down\" data-ng-bind=\"authUser.name\"></span>\r\n                </a>\r\n                <div class=\"dropdown-menu dropdown-ins dropdown-menu-right\" aria-labelledby=\"userDropdown\">\r\n                    <a class=\"dropdown-item waves-effect waves-light\" href=\"/dashboard\" target=\"_self\">My account</a>\r\n                    <a class=\"dropdown-item waves-effect waves-light\" href=\"#\" data-ng-click=\"logout()\">Log Out</a>\r\n\r\n                </div>\r\n            </li>\r\n        </ul>\r\n        <div class=\"float-right\">\r\n            <a href=\"#\" data-ng-click=\"toggleRight()\" class=\"button-collapse\"><i class=\"fa fa-bars\"></i></a>\r\n        </div>\r\n    </nav>\r\n</header>");}]);