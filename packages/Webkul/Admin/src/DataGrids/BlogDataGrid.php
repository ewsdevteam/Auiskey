<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class BlogDataGrid extends DataGrid
{
    protected $index = 'blog_id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('blogs as blog')
            ->leftjoin('admins','blog.author','=','admins.id')
            ->select('blog.id as blog_id', 'blog.*','admins.name')
            ->groupBy('blog.id');


        $this->addFilter('blog_id', 'blog.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'blog_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'image',
            'label'      => 'Image',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
            'wrapper'   => function($value){
                if(isset($value->image)){
                    echo '<img style = "width:100px" class = "img-thumbnail" src = "'.asset("public/storage/$value->image").'">';
                }else{
                    echo '<img style = "width:100px" class = "img-thumbnail" src = "'.asset("public/vendor/webkul/ui/assets/images/product/small-product-placeholder.png").'">';
                }                
            }
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'wrapper'    => function($value){
                echo '<h3>'.$value->title.'</h3>';
                echo '<p>'.substr($value->content,0,300).'...</p>';
                echo '<strong>Author: </strong>'.$value->name;
                echo '<br />';
                echo '<strong>Posted at: </strong>'.$value->posted_at;
            }
        ]);


        $this->addColumn([
            'index'      => 'is_published',
            'label'      => 'Published',
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
            'wrapper'    => function($value) {
                if ($value->is_published == 1) {
                    return 'Yes';
                } else {
                    return 'No';
                }
            },
        ]);

        $this->addColumn([
            'index'    =>  'action',
            'label'      => 'Action',
             'wrapper'    => function($value){
                echo '<a href = "news/edit/'.$value->blog_id.'" class = "edit" data-id = "'.$value->blog_id.'"><i class = "icon pencil-lg-icon"></i></a>';
                echo '<a href = "news/delete/'.$value->blog_id.'" class = "delete" data-id = "'.$value->blog_id.'"><i class = "icon trash-icon"></i></a>';
            }
            
        ]);
    }

/*    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.blog.blogs.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'GET',
            'route'        => 'admin.blog.blogs.delete',
            'icon'         => 'icon trash-icon',
        ]);
    }
    public function prepareMassActions()
    {

        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.blog.blogs.massdelete'),
            'method' => 'DELETE',
        ]);

    }*/
}