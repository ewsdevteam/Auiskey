@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.categories.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.blog.categories.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.blog.categories.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.blog.categories.add-title') }}
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.list.before') !!}

        <div class="page-content">
            {!! app('Webkul\Admin\DataGrids\BlogCategoryDataGrid')->render() !!}
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.list.after') !!}
    </div>
@stop