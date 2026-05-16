@extends('layouts.app')

@section('content')
<div class="max-w-[1000px] mx-auto px-6 py-20">
    {{-- Header --}}
    <div class="mb-20 text-left border-b border-gray-900 pb-8">
        <h1 class="text-3xl font-light tracking-[0.4em] uppercase mb-4">Returns & Shipping</h1>
        <p class="text-[11px] text-gray-400 uppercase tracking-widest font-medium">Customer Service / Policy</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-20">
        {{-- Section: Shipping --}}
        <div class="space-y-10">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-[0.2em] mb-6 border-s-4 border-black ps-4">Shipping Policy</h2>
                <div class="space-y-6 text-[12px] text-gray-600 leading-relaxed uppercase tracking-wider">
                    <p>
                        <strong class="text-black block mb-1">Domestic Delivery</strong>
                        We use JNE and J&T as our shipping partners. Orders are processed from our warehouse in Bandung.
                    </p>
                    <p>
                        <strong class="text-black block mb-1">Processing Time</strong>
                        All orders will be processed within 1-2 working days after payment confirmation. No shipping on Sundays and public holidays.
                    </p>
                    <p>
                        <strong class="text-black block mb-1">Tracking</strong>
                        Tracking numbers will be sent to your email or can be seen in your account dashboard 24 hours after shipment.
                    </p>
                </div>
            </div>
        </div>

        {{-- Section: Returns --}}
        <div class="space-y-10">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-[0.2em] mb-6 border-s-4 border-black ps-4">Return & Exchange</h2>
                <div class="space-y-6 text-[12px] text-gray-600 leading-relaxed uppercase tracking-wider">
                    <p>
                        <strong class="text-black block mb-1">Conditions</strong>
                        Items must be returned in original condition: unwashed, unworn, with all tags attached.
                    </p>
                    <p>
                        <strong class="text-black block mb-1">Time Limit</strong>
                        You have 3 days after receiving the package to request an exchange for size or manufacturing defects.
                    </p>
                    <p>
                        <strong class="text-black block mb-1">Shipping Fee</strong>
                        Return shipping fees are borne by the customer, unless the item is defective or we sent the wrong product.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="mt-20 border border-gray-100 p-12 text-center">
        <h4 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-6">Need more info?</h4>
        <div class="flex flex-col md:flex-row justify-center gap-8 md:gap-16">
            <div class="text-[11px] uppercase tracking-widest text-gray-500">
                Email: <span class="text-black font-medium">support@yomono.id</span>
            </div>
            <div class="text-[11px] uppercase tracking-widest text-gray-500">
                WhatsApp: <span class="text-black font-medium">+62 853-1111-1010</span>
            </div>
        </div>
    </div>
</div>
@endsection