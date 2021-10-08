<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\BidDataGrid;
use Webkul\Admin\DataGrids\ProductBidDataGrid;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderRepository;
use \Webkul\Sales\Repositories\OrderCommentRepository;
use DB;
class BidsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderCommentRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderCommentRepository
     */
    protected $orderCommentRepository;

    /**
     * CartRepository instance
     *
     * @var \Webkul\Checkout\Repositories\CartRepository
     */
    protected $cartRepository;

    /**
     * CartItemRepository instance
     *
     * @var \Webkul\Checkout\Repositories\CartItemRepository
     */
    protected $cartItemRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderCommentRepository  $orderCommentRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderCommentRepository $orderCommentRepository,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderRepository = $orderRepository;

        $this->orderCommentRepository = $orderCommentRepository;

        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            
            return app(BidDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    public function product_bids($id)
    {

    if (request()->ajax()) {
            
        return app(ProductBidDataGrid::class)->toJson();
    }

    return view($this->_config['view'],compact('id'));
    }
    

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $bid = DB::table('product_bids')->where('id',$id)->first();
        $cart = $this->cartRepository->find($bid->cart_id);
        
        return view($this->_config['view'], compact('cart','bid'));
    }

    /**
     * Cancel action for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $result = $this->orderRepository->cancel($id);

        if ($result) {
            session()->flash('success', trans('admin::app.response.cancel-success', ['name' => 'Order']));
        } else {
            session()->flash('error', trans('admin::app.response.cancel-error', ['name' => 'Order']));
        }

        return redirect()->back();
    }

    /**
     * Add comment to the order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function comment($id)
    {
        $data = array_merge(request()->all(), [
            'order_id' => $id,
        ]);

        $data['customer_notified'] = isset($data['customer_notified']) ? 1 : 0;

        Event::dispatch('sales.order.comment.create.before', $data);

        $comment = $this->orderCommentRepository->create($data);

        Event::dispatch('sales.order.comment.create.after', $comment);

        session()->flash('success', trans('admin::app.sales.orders.comment-added-success'));

        return redirect()->back();
    }
    // Approve the bid and covert into order
    public function accept($id){
        
        $bid = DB::table('product_bids')->where('cart_id',$id)->first();
        
        $cart = $this->cartRepository->find($id);
        
        $order = $this->orderRepository->create(Cart::prepareDataForOrderBid($cart));
        
        if(!empty($order)){
          
           $update = DB::table('product_bids')->where('cart_id',$id)->update(['status'=>'approved']);
           
           if($update){
                session()->flash('success', trans('admin::app.sales.bids.bid-approve-success')); 
           }       
        }
        else{
            session()->flash('error', trans('admin::app.sales.bids.bid-approve-error'));
        }
        
        return redirect()->route($this->_config['redirect']);

    }

    //Reject the bid
    public function reject($id){
       
        $update = DB::table('product_bids')->where('cart_id',$id)->update(['status'=>'rejected']);
           
        if($update){
            session()->flash('success', trans('admin::app.sales.bids.bid-reject-success')); 
        }       
        else{
            session()->flash('error', trans('admin::app.sales.bids.bid-reject-error'));
        }
        
        return redirect()->route($this->_config['redirect']);
    }

}