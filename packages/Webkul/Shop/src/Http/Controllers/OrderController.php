<?php

namespace Webkul\Shop\Http\Controllers;

use PDF;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Models\Order;
use Webkul\Checkout\Models\Cart;
use DB;
class OrderController extends Controller
{
    /**
     * Current customer.
     */
    protected $currentCustomer;

    /**
     * OrderrRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * InvoiceRepository object
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Order\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Order\Repositories\InvoiceRepository  $invoiceRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->middleware('customer');

        $this->currentCustomer = auth()->guard('customer')->user();

        $this->orderRepository = $orderRepository;

        $this->invoiceRepository = $invoiceRepository;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        $orders = Order::with('items')
                        ->where('customer_id', auth()->guard('customer')->user()->id)
                        ->paginate(8);
        
        return view($this->_config['view'],compact('orders'));
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $order = $this->orderRepository->findOneWhere([
            'customer_id' => $this->currentCustomer->id,
            'id'          => $id,
        ]);

        if (! $order) {
            abort(404);
        }

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Print and download the for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $invoice = $this->invoiceRepository->findOrFail($id);

        if ($invoice->order->customer_id !== $this->currentCustomer->id) {
            abort(404);
        }

        $pdf = PDF::loadView('shop::customers.account.orders.pdf', compact('invoice'))->setPaper('a4');

        return $pdf->download('invoice-' . $invoice->created_at->format('d-m-Y') . '.pdf');
    }

    /**
     * Cancel action for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        /* find by order id in customer's order */
        $order = $this->currentCustomer->all_orders()->find($id);

        /* if order id not found then process should be aborted with 404 page */
        if (! $order) {
            abort(404);
        }

        $result = $this->orderRepository->cancel($order);

        if ($result) {
            session()->flash('success', trans('admin::app.response.cancel-success', ['name' => 'Order']));
        } else {
            session()->flash('error', trans('admin::app.response.cancel-error', ['name' => 'Order']));
        }

        return redirect()->back();
    }

    public function bids()
    {
        $bids = Cart::with('items')
                ->join('product_bids','product_bids.cart_id','=','cart.id')
                ->where('cart.customer_id', auth()->guard('customer')->user()->id)
                ->addSelect('cart.*','product_bids.id as bid_id','product_bids.status as status')
                ->paginate(8);
        
        return view($this->_config['view'],compact('bids'));
    }

    public function bid_view($id)
    {
        $bid = Cart::with('items')
                ->join('product_bids','product_bids.cart_id','=','cart.id')
                ->where('cart.customer_id', auth()->guard('customer')->user()->id)
                ->where('product_bids.id', $id)
                ->addSelect('cart.*','product_bids.id as bid_id','product_bids.status as status')
                ->first();
        
        return view($this->_config['view'],compact('bid'));
    }
}