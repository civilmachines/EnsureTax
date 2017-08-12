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
                <div class="col-sm-5 mb-20"> <img src="images/abt.jpg" class="img-fluid" alt="EnsureTax" > </div>

                <div class="col-sm-7">
                    <h2 class="mb-2">About Us</h2>
                    <p>EnsureTax is a complete tax solution platform for all Indian tax needs with a team of more than100 professionals. Through EnsureTax, you can go ahead and undertake tax compliances under real time support of our in house professionals. We ensure that your tax calculations and compliances are done as per the tax laws, including the fact that all eligible claims for permissible tax benefits are made for your tax optimization. Our motive is to ensure that you file most accurate and complete tax return. We provide personalized assistance so that at no point of time there are any challenges being faced at your end. Our professionals are in house efficient subject matter experts with complete in depth tax knowledge. </p>

                    <h4>How we work :</h4>
                    <p>Just login and provide basic information, and leave the rest to us. Our inhouse experts shall call you within 24 hours to take the matter forward. You can be assured that your return are filed with an expert assistance with minimalistic involvement at your end.
                    </p>


                    <!--   <ul>
                           <li><i class="fa fa-check-square-o"></i> Clients</li>
                           <li><i class="fa fa-check-square-o"></i> People</li>
                           <li><i class="fa fa-check-square-o"></i> Integrity</li>
                           <li><i class="fa fa-check-square-o"></i> Quality</li>
                       </ul> -->
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