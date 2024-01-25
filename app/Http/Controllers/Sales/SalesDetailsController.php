<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Sales;
use App\Models\SalesDetails;
use App\Models\Product;
use App\Models\Setting;

class SalesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderBy('name_product')->get();
        $branch = Branch::orderBy('name')->get();

        // Check whether there are any transactions in progress
        if ($id_sales = session('id_sales')) {
            $sales = Sales::find($id_sales);
            $branchSelected = $sales->branch ?? new Branch();

            return view('sales_detail.index', compact('product', 'branch', 'id_sales', 'sales', 'branchSelected'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaction.create');
            } else {
                return redirect()->route('home');
            }
        }
    }

    // display the selected product in the datatable
    public function data($id)
    {
        try {
            $detail = SalesDetails::with('product')
            ->where('id_sales', $id)
            ->get();

            $data = array();
            $total = 0;
            $total_item = 0;

            foreach ($detail as $item) {
                $row = array();
                $row['code_product'] = '<span class="label label-success">'. $item->product['code_product'] .'</span';
                $row['name_product'] = $item->product['name_product'];
                $row['selling_price']  = '₱ '. format_uang($item->selling_price);
                $row['stock']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_sales_detail .'" value="'. $item->stock .'">';
                $row['subtotal']    = '₱ '. format_uang($item->subtotal);
                $row['action']        = '<div class="btn-group">
                                        <button onclick="deleteData(`'. route('transaction.destroy', $item->id_sales_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                    </div>';
                $data[] = $row;

                $total += $item->selling_price * $item->stock;
                $total_item += $item->stock;
            }
            $data[] = [
                'code_product' => '
                    <div class="total hide">'. $total .'</div>
                    <div class="total_item hide">'. $total_item .'</div>',
                'name_product'   => '',
                'selling_price'  => '',
                'stock'          => '',
                'subtotal'       => '',
                'action'         => '',
            ];

            return datatables()
                ->of($data)
                ->addIndexColumn()
                ->rawColumns(['action', 'code_product', 'stock'])
                ->make(true);
        } catch (\Throwable $th) {
            $message = 'Unable to display the data!';
            Session::flash('sweetAlertMessage', $message);
            Session::flash('showSweetAlert', true);
            Session::flash('sweetAlertIcon', 'error');
            Session::flash('sweetAlertTitle', 'Error');

            return redirect()->route('transaction.create')->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::where('id_product', $request->id_product)->first();

        // Check if the product exists
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding this product!'
            ], 500);
        }

        // Check if the product is out of stock
        if ($product->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product is out of stock and cannot be added to the sale.'
            ], 400);
        }

        // Continue with the sale if the product is available
        $detail = new SalesDetails();
        $detail->id_sales = $request->id_sales;
        $detail->id_product = $product->id_product;
        $detail->selling_price = $product->price_selling;
        $detail->stock = 1;
        $detail->subtotal = $product->price_selling;
        $detail->save();

        // Update the product stock
        $product->stock - 1;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product successfully added.'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $detail = SalesDetails::findOrFail($id);
            $request->validate([
                'stock' => 'required|integer|min:1',
            ]);

            // Check if the requested quantity exceeds the stock
            if ($request->stock > $detail->produck->stock) {
                return response()->json(['error' => 'Quantity exceeds available stock'], 400);
            }

            // Update quantity and subtotal
            $detail->stock = $request->stock;
            $detail->subtotal = $detail->selling_price * $request->stock;
            $detail->save();

            // Return success response
            return response()->json(['message' => 'Sales Details updated successfully']);
        } catch (\Throwable $th) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Unable to update quantity!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $detail = SalesDetails::find($id);
            $detail->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product removed successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error removing this product!'
            ], 500);
        }
    }

    // load the data realtime
    public function loadForm($total = 0, $change = 0)
    {
        $total_pay   = $total;
        $changes = ($change != 0) ? $change - $total_pay : 0;
        try {
            $data    = [
                'totalrp' => format_uang($total),
                'total_pay' => $total_pay,
                'total_pay_rp' => format_uang($total_pay),
                'change' => ucwords(terbilang($total_pay). ' Pesos'),
                'change_rp' => format_uang($changes),
                'change_change' => ucwords(terbilang($changes). ' Pesos'),
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            $message = 'Unable to load data!';
            Session::flash('sweetAlertMessage', $message);
            Session::flash('showSweetAlert', true);
            Session::flash('sweetAlertIcon', 'error');
            Session::flash('sweetAlertTitle', 'error');

            return redirect()->route('transaction.create')->withInput();
        }
    }
}
