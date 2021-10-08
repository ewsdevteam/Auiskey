@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.sales.bids.view-title', ['bid_id' => $bid->id]) }}
@stop

@section('content-wrapper')

    <div class="content full-page">

        <div class="page-header">

            <div class="page-title">
                <h1>
                    {!! view_render_event('sales.bid.title.before', ['cart' => $cart]) !!}

                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.sales.product.bids',['id'=>$bid->product_id]) }}'"></i>

                    {{ __('admin::app.sales.bids.view-title', ['bid_id' => $bid->id]) }}

                    {!! view_render_event('sales.bid.title.after', ['cart' => $cart]) !!}
                </h1>
            </div>

            <div class="page-action">
                {!! view_render_event('sales.bid.page_action.before', ['cart' => $cart]) !!}

                @if($bid->status == 'pending')    
                    <a href="{{ route('admin.sales.bids.reject', $cart->id) }}" class="btn btn-lg btn-primary" v-alert:message="'{{ __('admin::app.sales.bids.cancel-confirm-msg') }}'">
                        {{ __('admin::app.sales.bids.reject-btn-title') }}
                    </a>

                    <a href="{{ route('admin.sales.bids.accept', $cart->id) }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.sales.bids.accept-btn-title') }}
                    </a>
                @endif    

                {!! view_render_event('sales.cart.page_action.after', ['cart' => $cart]) !!}
            </div>
        </div>

        <div class="page-content">

            <tabs>
                {!! view_render_event('sales.bid.tabs.before', ['cart' => $cart]) !!}

                <tab name="{{ __('admin::app.sales.bids.info') }}" :selected="true">
                    <div class="sale-container">

                        <accordian :title="'{{ __('admin::app.sales.bids.bid-and-account') }}'" :active="true">
                            <div slot="body">

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.bids.bid-info') }}</span>
                                    </div>

                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.bids.bid-date') }}
                                            </span>

                                            <span class="value">
                                                {{ $cart->created_at }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.bid.created_at.after', ['cart' => $cart]) !!}

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.bids.bid-status') }}
                                            </span>

                                            <span class="value" style="text-transform:uppercase;">
                                                {{ $bid->status }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.cart.status_label.after', ['cart' => $cart]) !!}

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.bids.channel') }}
                                            </span>

                                            <span class="value">
                                                {{ $cart->channel_name }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.cart.channel_name.after', ['cart' => $cart]) !!}
                                    </div>
                                </div>

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.bids.account-info') }}</span>
                                    </div>

                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.bids.customer-name') }}
                                            </span>

                                            <span class="value">
                                                {{ $cart->customer_first_name." ".$cart->customer_last_name }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.cart.customer_full_name.after', ['cart' => $cart]) !!}

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.bids.email') }}
                                            </span>

                                            <span class="value">
                                                {{ $cart->customer_email }}
                                            </span>
                                        </div>

                                        {!! view_render_event('sales.cart.customer_email.after', ['cart' => $cart]) !!}

                                        @if (! is_null($cart->customer) && ! is_null($cart->customer->group))
                                            <div class="row">
                                                <span class="title">
                                                    {{ __('admin::app.customers.customers.customer_group') }}
                                                </span>

                                                <span class="value">
                                                    {{ $cart->customer->group->name }}
                                                </span>
                                            </div>
                                        @endif

                                        {!! view_render_event('sales.cart.customer_group.after', ['cart' => $cart]) !!}
                                    </div>
                                </div>

                            </div>
                        </accordian>

                        @if ($cart->billing_address || $cart->shipping_address)
                            <accordian :title="'{{ __('admin::app.sales.bids.address') }}'" :active="true">
                                <div slot="body">

                                    @if($cart->billing_address)
                                        <div class="sale-section">
                                            <div class="secton-title">
                                                <span>{{ __('admin::app.sales.bids.billing-address') }}</span>
                                            </div>

                                            <div class="section-content">
                                                @include ('admin::sales.address', ['address' => $cart->billing_address])

                                                {!! view_render_event('sales.cart.billing_address.after', ['cart' => $cart]) !!}
                                            </div>
                                        </div>
                                    @endif

                                    @if ($cart->shipping_address)
                                        <div class="sale-section">
                                            <div class="secton-title">
                                                <span>{{ __('admin::app.sales.bids.shipping-address') }}</span>
                                            </div>

                                            <div class="section-content">
                                                @include ('admin::sales.address', ['address' => $cart->shipping_address])

                                                {!! view_render_event('sales.cart.shipping_address.after', ['cart' => $cart]) !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </accordian>
                        @endif

                        <accordian :title="'{{ __('admin::app.sales.bids.payment-and-shipping') }}'" :active="true">
                            <div slot="body">

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.bids.payment-info') }}</span>
                                    </div>

                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.bids.payment-method') }}
                                            </span>

                                            <span class="value">
                                                {{ core()->getConfigData('sales.paymentmethods.' . $cart->payment->method . '.title') }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.bids.currency') }}
                                            </span>

                                            <span class="value">
                                                {{ $cart->cart_currency_code }}
                                            </span>
                                        </div>

                                        @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($cart->payment->method); @endphp

                                        @if (! empty($additionalDetails))
                                            <div class="row">
                                                <span class="title">
                                                    {{ $additionalDetails['title'] }}
                                                </span>

                                                <span class="value">
                                                    {{ $additionalDetails['value'] }}
                                                </span>
                                            </div>
                                        @endif

                                        {!! view_render_event('sales.cart.payment-method.after', ['cart' => $cart]) !!}
                                    </div>
                                </div>

                                @if ($cart->shipping_address)
                                    <div class="sale-section">
                                        <div class="secton-title">
                                            <span>{{ __('admin::app.sales.bids.shipping-info') }}</span>
                                        </div>

                                        <div class="section-content">
                                            <div class="row">
                                                <span class="title">
                                                    {{ __('admin::app.sales.bids.shipping-method') }}
                                                </span>

                                                <span class="value">
                                                    {{ $cart->shipping_title }}
                                                </span>
                                            </div>

                                            <div class="row">
                                                <span class="title">
                                                    {{ __('admin::app.sales.bids.shipping-price') }}
                                                </span>

                                                <span class="value">
                                                    {{ core()->formatBasePrice($cart->base_shipping_amount) }}
                                                </span>
                                            </div>

                                            {!! view_render_event('sales.cart.shipping-method.after', ['cart' => $cart]) !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </accordian>

                        <accordian :title="'{{ __('admin::app.sales.bids.products-bidded') }}'" :active="true">
                            <div slot="body">

                                <div class="table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin::app.sales.bids.SKU') }}</th>
                                                <th>{{ __('admin::app.sales.bids.product-name') }}</th>
                                                <th>{{ __('admin::app.sales.bids.price') }}</th>
                                                <th>{{ __('admin::app.sales.bids.subtotal') }}</th>
                                                <th>{{ __('admin::app.sales.bids.tax-percent') }}</th>
                                                <th>{{ __('admin::app.sales.bids.tax-amount') }}</th>
                                                @if ($cart->base_discount_amount > 0)
                                                    <th>{{ __('admin::app.sales.bids.discount-amount') }}</th>
                                                @endif
                                                <th>{{ __('admin::app.sales.bids.grand-total') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        
                                            @foreach ($cart->items as $item)                    
                                                <tr>
                                                    <td>
                                                        {{ $item->sku }}
                                                    </td>

                                                    <td>
                                                        {{ $item->name }}

                                                        @if (isset($item->additional['attributes']))
                                                            <div class="item-options">

                                                                @foreach ($item->additional['attributes'] as $attribute)
                                                                    <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                                @endforeach

                                                            </div>
                                                        @endif
                                                    </td>

                                                    <td>{{ core()->formatBasePrice($item->base_price) }}</td>

                                                    <td>{{ core()->formatBasePrice($item->base_total) }}</td>

                                                    <td>{{ $item->tax_percent }}%</td>

                                                    <td>{{ core()->formatBasePrice($item->base_tax_amount) }}</td>

                                                    @if ($cart->base_discount_amount > 0)
                                                        <td>{{ core()->formatBasePrice($item->base_discount_amount) }}</td>
                                                    @endif

                                                    <td>{{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}</td>
                                                </tr>
                                            @endforeach
                                    </table>
                                </div>

                                <div class="summary-comment-container">
                                {{-- <div class="comment-container">
                                        <form action="{{ route('admin.sales.orders.comment', $cart->id) }}" method="post" @submit.prevent="onSubmit">
                                            @csrf()

                                            <div class="control-group" :class="[errors.has('comment') ? 'has-error' : '']">
                                                <label for="comment" class="required">{{ __('admin::app.sales.bids.comment') }}</label>
                                                <textarea v-validate="'required'" class="control" id="comment" name="comment" data-vv-as="&quot;{{ __('admin::app.sales.bids.comment') }}&quot;"></textarea>
                                                <span class="control-error" v-if="errors.has('comment')">@{{ errors.first('comment') }}</span>
                                            </div>

                                            <div class="control-group">
                                                <span class="checkbox">
                                                    <input type="checkbox" name="customer_notified" id="customer-notified" name="checkbox[]">
                                                    <label class="checkbox-view" for="customer-notified"></label>
                                                    {{ __('admin::app.sales.bids.notify-customer') }}
                                                </span>
                                            </div>

                                            <button type="submit" class="btn btn-lg btn-primary">
                                                {{ __('admin::app.sales.bids.submit-comment') }}
                                            </button>
                                        </form>
                                       
                                        <ul class="comment-list">
                                            @foreach ($cart->comments()->cartBy('id', 'desc')->get() as $comment)
                                                <li>
                                                    <span class="comment-info">
                                                        @if ($comment->customer_notified)
                                                            {!! __('admin::app.sales.bids.customer-notified', ['date' => $comment->created_at]) !!}
                                                        @else
                                                            {!! __('admin::app.sales.bids.customer-not-notified', ['date' => $comment->created_at]) !!}
                                                        @endif
                                                    </span>

                                                    <p>{{ $comment->comment }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                        
                                    </div>
                                    --}}   
                                    <table class="sale-summary">
                                        <tr>
                                            <td>{{ __('admin::app.sales.bids.subtotal') }}</td>
                                            <td>-</td>
                                            <td>{{ core()->formatBasePrice($cart->base_sub_total) }}</td>
                                        </tr>

                                        @if ($cart->haveStockableItems())
                                            <tr>
                                                <td>{{ __('admin::app.sales.bids.shipping-handling') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatBasePrice($cart->base_shipping_amount) }}</td>
                                            </tr>
                                        @endif

                                        @if ($cart->base_discount_amount > 0)
                                            <tr>
                                                <td>
                                                    {{ __('admin::app.sales.bids.discount') }}

                                                    @if ($cart->coupon_code)
                                                        ({{ $cart->coupon_code }})
                                                    @endif
                                                </td>
                                                <td>-</td>
                                                <td>{{ core()->formatBasePrice($cart->base_discount_amount) }}</td>
                                            </tr>
                                        @endif

                                        <tr class="bcart">
                                            <td>{{ __('admin::app.sales.bids.tax') }}</td>
                                            <td>-</td>
                                            <td>{{ core()->formatBasePrice($cart->base_tax_amount) }}</td>
                                        </tr>

                                        <tr class="bold">
                                            <td>{{ __('admin::app.sales.bids.grand-total') }}</td>
                                            <td>-</td>
                                            <td>{{ core()->formatBasePrice($cart->base_grand_total) }}</td>
                                        </tr>

                                        <tr class="bold">
                                            <td>{{ __('admin::app.sales.bids.total-paid') }}</td>
                                            <td>-</td>
                                            <td>{{ core()->formatBasePrice($cart->base_grand_total_invoiced) }}</td>
                                        </tr>

                                        <tr class="bold">
                                            <td>{{ __('admin::app.sales.bids.total-refunded') }}</td>
                                            <td>-</td>
                                            <td>{{ core()->formatBasePrice($cart->base_grand_total_refunded) }}</td>
                                        </tr>

                                        <tr class="bold">
                                            <td>{{ __('admin::app.sales.bids.total-due') }}</td>

                                            <td>-</td>

                                            @if($cart->status !== 'canceled')
                                                <td>{{ core()->formatBasePrice($cart->base_total_due) }}</td>
                                            @else
                                                <td id="due-amount-on-cancelled">{{ core()->formatBasePrice(0.00) }}</td>
                                            @endif
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </accordian>

                    </div>
                </tab>

                {!! view_render_event('sales.cart.tabs.after', ['cart' => $cart]) !!}
            </tabs>
        </div>

    </div>
@stop
