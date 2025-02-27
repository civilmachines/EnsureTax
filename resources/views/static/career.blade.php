@extends('master.master')
@section('css')
<link rel="stylesheet" href="{{asset('css/static.min.css')}}" media="screen">
@stop
@section('content')
<hs-header></hs-header>
<div class="about_banner">
    <div class="col-md-6 col-sm-12 center-block caption">
        <h1> Career</h1>
    </div>
</div>
<main>
    <div class="static_page">
        <div class="container pb-4">
            <div class="card">
                <div class="card-block p-5">
                    <h3 class="section-heading mb-2">Teams & Roles</h3>


                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="card">
                            <div class="card-header danger-color-dark" role="tab" id="headingOne"><a
                                    data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne">
                                    <h5 class="mb-0 white-text">Consultant (Noida) <i
                                            class="fa fa-angle-down rotate-icon"></i></h5>
                                </a></div>


                            <div id="collapseOne" class="collapse show" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <div class="card-block p-3 pt-2">
                                    <h4>Job Description</h4>
                                    <p>At 101housing, we believe rental services can be done better with people who
                                        are familiar with their locality. We therefore invite people who understanding
                                        their locality better and have passion to serve prospective tenants. You will work
                                        on part time basis and from the convenience of your home, if you want.</p>
                                    <h4><strong>Skill</strong></h4>
                                    <p><i class="fa fa-long-arrow-right"></i> Understanding of your locality, Basic
                                        Computer, Communication</p>
                                    <h4><strong>Key Skill</strong></h4>

                                    <ul>
                                        <li><i class="fa fa-long-arrow-right"></i> Good communication skills</li>
                                        <li><i class="fa fa-long-arrow-right"></i> Basic computer skills</li>
                                        <li><i class="fa fa-long-arrow-right"></i> Able to handle customer queries
                                            diligently
                                        </li>
                                        <li><i class="fa fa-long-arrow-right"></i> Quick and fast learner who can
                                            adapt
                                            to new ideas
                                        </li>
                                    </ul>
                                    {{--<a href="javascript:void(0);" class="btn btn-unique btn-lg"--}}
                                       {{--data-ng-click="app.openCareerPopup('Leasing Consultant')">Apply</a>--}}

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header danger-color-dark" role="tab" id="headingTwo">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                   href="#collapseTwo"
                                   aria-expanded="false" aria-controls="collapseTwo">
                                    <h5 class="mb-0 white-text">
                                        Tele Caller (Noida) <i class="fa fa-angle-down rotate-icon"></i>
                                    </h5>
                                </a>
                            </div>
                            <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="card-block p-3 pt-2">
                                    <h4>Job Description</h4>
                                    <p>If you are diligent at calling people and enjoy interacting with them, we have an ideal job for you. You may work on part time basis and from the convenience of your home, if you want.</p>
                                    <h4><strong>Skill</strong></h4>
                                    <p><i class="fa fa-long-arrow-right"></i> Basic Computer, Communication (English, Hindi)</p>
                                    <h4><strong>Key Skill</strong></h4>

                                    <ul>
                                        <li><i class="fa fa-long-arrow-right"></i> Good communication skills, confident in interacting with people on phone</li>
                                        <li><i class="fa fa-long-arrow-right"></i> Basic computer skills</li>
                                        <li><i class="fa fa-long-arrow-right"></i> Able to handle customer queries diligently
                                        </li>

                                    </ul>
                                    {{--<a href="javascript:void(0);" class="btn btn-unique btn-lg"--}}
                                       {{--data-ng-click="app.openCareerPopup('Leasing Consultant')">Apply</a>--}}
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header danger-color-dark" role="tab" id="headingThree">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                   href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <h5 class="mb-0 white-text">
                                        Channel Manager (Noida) <i class="fa fa-angle-down rotate-icon"></i>
                                    </h5>
                                </a>
                            </div>
                            <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="card-block p-3 pt-2">
                                    <h4>Job Description</h4>
                                    <p>You will be responsible to appoint channel partners, appoint and develop new channel partners/freelancers in a given area. It is a target oriented job. You must have good knowledge of the area you want to operate in.</p>
                                    <h4><strong>Skill</strong></h4>
                                    <p><i class="fa fa-long-arrow-right"></i> Management, Develop Concepts and Ideas, Implement them on ground</p>
                                    <h4><strong>Key Skill</strong></h4>

                                    <ul>
                                        <li><i class="fa fa-long-arrow-right"></i> Manage Team of freelancer/channel partners</li>
                                        <li><i class="fa fa-long-arrow-right"></i> Understand the dynamics of rental market</li>
                                        <li><i class="fa fa-long-arrow-right"></i> Develop concepts and implement</li>
                                        <li><i class="fa fa-long-arrow-right"></i> Develop an area aligned to the objective of the organization
                                        </li>
                                    </ul>
                                    {{--<a href="javascript:void(0);" class="btn btn-unique btn-lg"--}}
                                       {{--data-ng-click="app.openCareerPopup('Leasing Consultant')">Apply</a>--}}

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
</main>
<hs-footer></hs-footer>
@endsection
{{--@section('script')--}}
{{-- < script type = 'text/javascript' src = "{{asset('js/static.min.js')}}"></script>--}}
{{--@stop--}}