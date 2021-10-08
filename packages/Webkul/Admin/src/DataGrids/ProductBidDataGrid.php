<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Ui\DataGrid\Traits\ProvideDataGridPlus;
use Illuminate\Http\Request;

class ProductBidDataGrid extends DataGrid
{

    use ProvideDataGridPlus;

    protected $index = 'bid_id';

    // protected $sortOrder = 'desc';
       
    public function prepareQueryBuilder()
    {       $id = request()->id;
            $queryBuilder = DB::table('product_bids')
                    ->join('product_flat','product_flat.product_id','=','product_bids.product_id')
                    ->leftJoin('product_images','product_images.product_id','=','product_bids.product_id')
                    ->where('product_flat.status',1)
                    ->orderBy('product_bids.created_at','desc')
                    ->where('product_bids.product_id',$id)
                    ->addSelect('product_flat.product_id as id','product_flat.sku','product_flat.name','product_bids.bid_price','product_bids.status','product_bids.created_at as bid_date','product_bids.created_at as expiry_date','product_bids.id as bid_id','product_bids.cart_id')
                    ->addSelect(DB::raw('product_images.path'));
                                                   
         $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'bid_id',
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
                    return '<img src = "/vendor/webkul/ui/assets/images/product/small-product-placeholder.png" style = "width: 100px" >';
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
            'index'      => 'bid_price',
            'label'      => trans('admin::app.datagrid.bid-price'),
            'type'       => 'price',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'price',
            'sortable'   => true,
            'searchable' => true,
            'closure'    => true,
            'filterable' => true,
            'wrapper' => function ($value) {
                if ($value->status == 'processing') {
                    return '<span class="badge badge-md badge-success">' . trans('admin::app.sales.bids.bid-status-processing') . '</span>';
                } elseif ($value->status == 'approved') {
                    return '<span class="badge badge-md badge-success">' . trans('admin::app.sales.bids.bid-status-success') . '</span>';
                } elseif ($value->status == "rejected") {
                    return '<span class="badge badge-md badge-danger">' . trans('admin::app.sales.bids.bid-status-rejected') . '</span>';
                } elseif ($value->status == "closed") {
                    return '<span class="badge badge-md badge-info">' . trans('admin::app.sales.bids.bid-status-closed') . '</span>';
                } elseif ($value->status == "pending") {
                    return '<span class="badge badge-md badge-warning">' . trans('admin::app.sales.bids.bid-status-pending') . '</span>';
                } elseif ($value->status == "pending_payment") {
                    return '<span class="badge badge-md badge-warning">' . trans('admin::app.sales.bids.bid-status-pending-payment') . '</span>';
                } elseif ($value->status == "fraud") {
                    return '<span class="badge badge-md badge-danger">' . trans('admin::app.sales.bids.bid-status-fraud') . '</span>';
                }
            },
        ]);
        
        $this->addColumn([
            'index'      => 'bid_date',
            'label'      => trans('admin::app.datagrid.bid-placed'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'expiry_date',
            'label'      => trans('admin::app.datagrid.expired-at'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function ($value) {
              return date('Y-m-d H:i:s',strtotime('+30 days'.$value->bid_date));
            }
        ]);
        

    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'admin.sales.bid.view',
            'icon'   => 'icon eye-icon',
        ]);
    }


}
