@extends('master.master')
@section('css')
<link rel="stylesheet" href="{{asset('css/static.min.css')}}" media="screen">
@stop
@section('content')
<hs-header></hs-header>
<main>
    <div class="static_page">
        <div class="container pb-4">
            <div class="row">
                <div class="col-sm-12 text-center">
                <h2 class="mb-2">Our Services</h2>
                </div>
                
                <div class="col-sm-4">
                    <div class="category-group">
                        <div class="icon"> <img src="images/svg/trusted_advisory.svg" alt="Trusted Advisory Services"> </div>
                        <h4>Tax Advisory</h4>
                        <p>EnsureTax.com Advisory offers clients a broad range of fully integrated tax services. Our team will bring you technical knowledge, business experience and consistent methodologies to service your requirement.</p>

                        <a href="#" class="btn btn-success">More Details</a> </div>
                </div>
                <div class="col-sm-4">
                    <div class="category-group">
                        <div class="icon"> <img src="images/svg/business_process.svg" alt="Business Process Management"> </div>
                        <h4>Tax Litigations</h4>
                        <p>The team member's experience encompasses all aspects of the tax litigation process, including the preparation of applications to appeal, petitions, briefs, written submissions, and other pleadings. </p>

                        <a href="#" class="btn btn-success">More Details</a> </div>
                </div>
                <div class="col-sm-4">
                    <div class="category-group">
                        <div class="icon"> <img src="images/svg/portfolio_management.svg" alt="Portfolio Management"> </div>
                        <h4>Business &amp; Risk Advisory</h4>
                        <p>EnsureTax.com Risk and Advisory practice offers a complete suite of services to its client, encompassing fiancial and operating risk assessment, business and financial diligences and regulatory...</p>

                        <a href="#" class="btn btn-success">More Details</a> </div>
                </div>
                <div class="col-sm-4">
                    <div class="category-group">
                        <div class="icon"> <img src="images/svg/delivery_services.svg" alt="Delivery Services"> </div>
                        <h4>Transaction Advisory</h4>
                        <p>Transaction advisory including planning for achieving Mergers, Acquisitions, De-mergers, and Corporate re-organizations</p>

                        <a href="#" class="btn btn-success">More Details</a> </div>
                </div>

                <div class="col-sm-4">
                    <div class="category-group">
                        <div class="icon"> <img src="images/svg/managed_services.svg" alt="Managed Services"> </div>
                        <h4>Assurange &amp; Auditing</h4>
                        <p>EnsureTax.com provides independent audit services that enhances the reliability of information used by investors and other stake holders. Our audit methodology focuses on maintaining transparency...</p>
                        <a href="#" class="btn btn-success">More Details</a> </div>
                </div>
                <div class="col-sm-4">
                    <div class="category-group">
                        <div class="icon"> <img src="images/svg/reporting_services.svg" alt="Reporting Services"> </div>
                        <h4>Regulatory Advisory</h4>
                        <p>EnsureTax.com Advisory offers clients a broad range of fully integrated tax services. Our team will bring you technical knowledge, business experience and consistent methodologies to service your requirement.</p>
                        <a href="#" class="btn btn-success">More Details</a> </div>
                </div>
                <div class="col-sm-4">
                    <div class="category-group">
                        <div class="icon"> <img src="images/svg/risk_management.svg" alt="Risk Management"> </div>
                        <h4>Real Estate</h4>
                        <p>EnsureTax.com Advisory Real Estate focusses on creating long term investment portfolio for our clients. The domain extends to all possible avenues in the real estate sector, including residential...</p>
                        <a href="#" class="btn btn-success">More Details</a> </div>
                </div>
                <div class="col-sm-4">
                    <div class="category-group">
                        <div class="icon"> <img src="images/svg/human.svg" alt="Human Capital"> </div>
                        <h4>NETS</h4>
                        <p>This is a unique concept started in November 2015 with a specific focus on start ups, SMEs and MEs.</p>
                        <a href="#" class="btn btn-success">More Details</a> </div>
                </div>


                <div class="col-sm-4">
                    <div class="category-group">
                        <div class="icon"> <img src="images/svg/human.svg" alt="Human Capital"> </div>
                        <h4>Outsourcing</h4>
                        <p>Felix Advisory offers clients a broad range of fully integrated tax services. Our team will bring you technical knowledge, business experience and consistent methodologies to service your requirement.</p>
                        <a href="#" class="btn btn-success">More Details</a> </div>
                </div>

            </div>






        </div>
    </div>
</main>
<hs-footer></hs-footer>
@endsection
{{--@section('script')--}}
{{--<script type='text/javascript' src="{{asset('js/static.min.js')}}"></script>--}}
{{--@stop--}}