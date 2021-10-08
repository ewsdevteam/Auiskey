@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.news.blogs.title') }}
@stop
@push('scripts')
    
    <script type = "text/javascript">
    $(document).ready(function(){
    
        $('.search-filter').hide();
    })
    

    </script>
@endpush
@section('content')
    <div class="content" style="height: 100%;">
        <?php $locale = request()->get('locale') ?: null; ?>
        <?php $channel = request()->get('channel') ?: null; ?>
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.news.news.title') }}</h1>
            </div>

            <div class="page-action">
            
                <a href="{{ route('admin.news.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.news.news.add-news-btn-title') }}
                </a>
            </div>
        </div>



        <div class="page-content">
            @inject('news', 'Webkul\Admin\DataGrids\BlogDataGrid')
            {!! $news->render() !!}
        </div>


    </div>

@stop

