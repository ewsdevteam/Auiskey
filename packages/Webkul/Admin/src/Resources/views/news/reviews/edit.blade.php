@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.reviews.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.news.reviews.update', $review->id) }}">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ route('admin.dashboard.index') }}';"></i>

                        {{ __('admin::app.customers.reviews.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.account.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('admin::app.account.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group">
                                <label for="name" class="required">{{ __('admin::app.customers.reviews.status') }}</label>
                                <select  class="control" name="status">
                                    <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>
                                        {{ __('admin::app.customers.reviews.approved') }}
                                    </option>

                                    <option value="2" {{ $review->status == 2 ? 'selected' : ''}}>
                                        {{ __('admin::app.customers.reviews.disapproved') }}
                                    </option>

                                    <option value="0" {{ $review->status == 0 ? 'selected' : ''}}>
                                        {{ __('admin::app.customers.reviews.pending') }}
                                    </option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="name" >{{ __('admin::app.customers.reviews.comment') }}</label>
                                <textarea  class="control" disabled> {{ $review->comment }}</textarea>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop