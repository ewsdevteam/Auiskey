<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Ui\DataGrid\Traits\ProvideDataGridPlus;

class BidDataGrid extends DataGrid
{
    use ProvideDataGridPlus;

    protected $index = 'id';

    // protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_flat')
                    ->join('product_bids','product_flat.product_id','=','product_bids.product_id')
                    ->leftJoin('product_images','product_images.product_id','=','product_bids.product_id')
                    ->where('product_flat.status',1)
                    ->groupBy('product_bids.product_id')
                    ->orderBy('product_bids.created_at','desc')
                    ->addSelect('product_flat.product_id as id','product_flat.sku','product_flat.name',DB::raw('count(product_bids.product_id) as totalBids'),DB::raw('max(product_bids.created_at) as last_bid'))
                    ->addSelect(DB::raw('max(product_bids.bid_price) as highest_bid'),DB::raw('min(product_bids.bid_price) as lowest_bid'))
                    ->addSelect(DB::raw('product_images.path')) 
                    ->addSelect(['bidPending' => function($query){
                        $query->select(DB::raw('count(product_bids.id)'))
                                ->from('product_bids')
                                ->where('product_bids.status','pending')
                                // ->whereRaw('product_bids.product_id = product_images.product_id')
                                ->count();
                    }]);
                                                      
         $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'sku',
            'label'      => trans('admin::app.datagrid.sku'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'image',
            'label'      => trans('admin::app.datagrid.image'),
            'type'       => 'string',
            'closure'    => true,
             'wrapper'    => function($value){
                if($value->path != ''){
                    return '<img src = "'.asset('storage/'.$value->path).'" style = "width: 100px" >';
                }
                else{
                    return '<img src = "/vendor/webkul/ui/assets/images/product/small-product-placeholder.png" style = "width: 100px;" >';
                } 
                
            }
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.product-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'totalBids',
            'label'      => trans('admin::app.datagrid.total-bids'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'highest_bid',
            'label'      => trans('admin::app.datagrid.highest-bid'),
            'type'       => 'price',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'lowest_bid',
            'label'      => trans('admin::app.datagrid.lowest-bid'),
            'type'       => 'price',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'last_bid',
            'label'      => trans('admin::app.datagrid.lastest-bid'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'admin.sales.product.bids',
            'icon'   => 'icon eye-icon',
        ]);
    }


}
