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
                <div class="col-sm-12 text-center mb-2">
                    <h2 class="mb-1">Frequently asked questions (FAQ)</h2>
                    <hr>
                </div>
            </div>
            <div id="faq-item-1" class="faq-thread">
                <h4 class="faq-title">Why is 101lease not a listing platform?</h4>
                <p>101Lease is not in the business of exposing your property on its website. Instead, we encourage owners to post their property so we can show it to prospective tenants only. Owners do not get unnecessary calls or visit; tenants get the property of their choice - saves time for both.</p>
              </div>
            
            <div id="faq-item-1" class="faq-thread">
                <h4 class="faq-title">Do I deal with 101?</h4>
                <p>101 means 'one-to-one'. Unlike listing platforms where you have to deal with multiple owners/tenants; with 101, you are dealing only with us. 101lease offers you related services that saves you time and resource.</p>
              </div>
            
            <div id="faq-item-2" class="faq-thread">
                <h4 class="faq-title">How do you simplify rental experience?</h4>
                <p>You work only with 101lease. Our match making engine let's you choose the property of your choice. We serve you through the process of your search, agreement and beyond, all through optimal use of Technology and service support of 101lease.</p>
              </div>
            
            <div id="faq-item-3" class="faq-thread">
                <h4 class="faq-title">Do I get the rental done quickly with 101lease?</h4>
                <p>101 is an aggregation platform where property owners and tenants show their pleasure of posting their specify needs. The aggregation ensures there are enough owners and tenants at any given point of time. Utilizing the proprietary match-making engine for optimal results tenants are able to find a leasable property quickly.</p>
              </div>
            
            <div id="faq-item-4" class="faq-thread">
                <h4 class="faq-title">What service do I get as tenant?</h4>
                <p>After you post your rental requirement on our platform, we help you find the right match of property. We also facilitate your visit to the properties of your choice and help you zero down on the best option. We also help you complete the agreements, negotiations before you move in. During your stay, 101lease is just a phone call away to facilitate any service you may need.</p>
              </div>
            
            <div id="faq-item-5" class="faq-thread">
                <h4 class="faq-title">What service do I get as owner?</h4>
                <p>No more the owner needs to list the property on listing platform. After you register your property with us, everything else from tenant search, property tour, agreements and other services are taken care of by us. No hassles, no unnecessary calls, visits by strangers.</p>
              </div>
            
            <div id="faq-item-6" class="faq-thread">
                <h4 class="faq-title">How do I book a shared accommodation through 101?</h4>
                <p>It is very easy. Browse through the shared accommodation section of 101lease. Select the property you want to book a room/bed by completing the payment process by clicking Book Now button. You may ask for a visit to the shared accommodating properties, if you want.</p>
              </div>
            
            <div id="faq-item-7" class="faq-thread">
                <h4 class="faq-title">I have a property to offer on shared accommodation. How can 101lease help?</h4>
                <p>Launching your shared accommodation in 101lease is easy. Login to the platform (after registering) and enter the details of the property. You will get a call back from 101lease (or, you can call us) to complete the pricing and agreement formalities. Right after that, your property is online for business. You can also get special promotional schemes with 101lease on your property.</p>
              </div>
            
            <div id="faq-item-8" class="faq-thread">
                <h4 class="faq-title">I don't want to manage my property on day to day basis. Can 101 help?</h4>
                <p>101lease is ideally suited for your specific need because as an Asset management company, we can not only maintain your property, we shall lease it out to tenants and take care of the complete life cycle. As owner, you can check the proceedings through our technology platform from wherever you are in the world.</p>
              </div>

        </div>
    </div>
</main>
<hs-footer></hs-footer>
@endsection
{{--@section('script')--}}
{{--<script type='text/javascript' src="{{asset('js/static.min.js')}}"></script>--}}
{{--@stop--}}