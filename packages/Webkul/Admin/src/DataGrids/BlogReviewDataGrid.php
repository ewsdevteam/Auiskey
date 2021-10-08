<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class BlogReviewDataGrid extends DataGrid
{
    protected $index = 'blog_review_id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('blog_reviews as br')
            ->leftjoin('blogs as bg', 'br.blog_id', '=', 'bg.id')
            ->select('br.id as blog_review_id', 'br.comment', 'bg.title as blog_title', 'br.status as blog_review_status');

        $this->addFilter('blog_review_id', 'br.id');
        $this->addFilter('blog_review_status', 'br.status');
        $this->addFilter('blog_title', 'bg.title');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'blog_review_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'comment',
            'label'      => trans('admin::app.datagrid.comment'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'blog_title',
            'label'      => 'Blog Title',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'blog_review_status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'width'      => '100px',
            'filterable' => true,
            'closure'    => true,
            'wrapper'    => function ($value) {
                if ($value->blog_review_status == 1) {
                    return '<span class="badge badge-md badge-success">' . trans('admin::app.datagrid.approved') . '</span>';
                } elseif ($value->blog_review_status == 0) {
                    return '<span class="badge badge-md badge-warning">' . trans('admin::app.datagrid.pending') . '</span>';
                } elseif ($value->blog_review_status == 2) {
                    return '<span class="badge badge-md badge-danger">' . trans('admin::app.datagrid.disapproved') . '</span>';
                }
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.news.reviews.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'GET',
            'route'  => 'admin.news.reviews.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
        // $this->addMassAction([
        //     'type'  => 'delete',
        //     'label'  => trans('admin::app.datagrid.delete'),
        //     'action' => route('admin.customer.review.massdelete'),
        //     'method' => 'DELETE',
        // ]);

        // $this->addMassAction([
        //     'type'    => 'update',
        //     'label'   => trans('admin::app.datagrid.update-status'),
        //     'action'  => route('admin.customer.review.massupdate'),
        //     'method'  => 'PUT',
        //     'options' => [
        //         trans('admin::app.customers.reviews.pending')     => 0,
        //         trans('admin::app.customers.reviews.approved')    => 1,
        //         trans('admin::app.customers.reviews.disapproved') => 2,
        //     ],
        // ]);
    }
}