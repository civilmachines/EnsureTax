@extends('master.master')
@section('css')
<link rel="stylesheet" href="{{asset('css/static.min.css')}}" media="screen">
@stop
@section('content')
<hs-header></hs-header>

<main>
    <div class="static_page">
        <div class="container pb-4">

            <section>
                <h1 class="section-heading">Contact Us</h1>
                <p class=" text-center mb-2">You can contact us by submitting the form below.</p>
                <div class="row">
                    <div ui-view="contactUs" class="col-md-8">
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-4">
                        <h4>Corporate Office</h4>
                        <ul class="contact-icons">
                            <li>
                                <p><i class="fa fa-map-marker"></i> &nbsp; Plot no. 35, Arjun Marg, <br> &nbsp; &nbsp; &nbsp; DLF phase-1, Gurgaon -122002</p>
                            </li>
                            <li>
                                <p><i class="fa fa-phone"></i> &nbsp; (+91) 995858 2028</p>
                            </li>
                            <li>
                                <p><i class="fa fa-envelope"></i> &nbsp; info@ensuretax.com</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
<hs-footer></hs-footer>
@endsection
@section('script')
<script type='text/javascript' src="{{asset('js/static.min.js')}}"></script>
@stop