<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Product;
use App\Models\Supplier;

class PurchaseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_purchase = session('id_purchase');
        $product = Product::orderBy('name_product')->get();
        $supplier = Supplier::find(session('id_supplier'));
        $discount = Purchase::find($id_purchase)->discount ?? 0;

        if (! $supplier) {
            abort(404);
        }

        return view('purchase_detail.index', compact('id_purchase', 'product', 'supplier', 'discount'));
    }

    // displaying the product in the datatable
    public function data($id)
    {
        $detail = PurchaseDetails::with('product')
            ->where('id_purchase', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['code_product'] = '<span class="label label-success">'. $item->product['code_product'] .'</span';
            $row['name_product'] = $item->product['name_product'];
            $row['price_purchase']  = '₱ '.format_uang ($item->price_purchase);
            $row['stock']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_purchase_detail .'" value="'. $item->stock .'">';
            $row['subtotal']    = '₱ '.format_uang ($item->subtotal);
            $row['action']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('purchase_detail.destroy', $item->id_purchase_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->price_purchase * $item->stock;
            $total_item += $item->stock;
        }
        $data[] = [
            'code_product' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'name_product' => '',
            'price_purchase'  => '',
            'stock'      => '',
            'subtotal'    => '',
            'action'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['action', 'code_product', 'stock'])
            ->make(true);
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
        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching the product!'
            ], 500);
        }

        $detail = new PurchaseDetails();
        $detail->id_purchase = $request->id_purchase;
        $detail->id_product = $product->id_product;
        $detail->price_purchase = $product->price_purchase;
        $detail->stock = 1;
        $detail->subtotal = $product->price_purchase;
        $detail->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product added successfully'
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
        $detail = PurchaseDetails::findOrFail($id);

        $detail->update([
            'stock' => $request->stock,
            'subtotal' => $detail->price_purchase * $request->stock,
        ]);

        return response()->json('Purchase updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product_detail = PurchaseDetails::find($id);
        try {
            $product_detail->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product successfully removed!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product details not found!'
            ], 404);
        }
        return response(null, 204);
    }


    public function loadForm($discount, $total)
    {
        $total_pay = $total - ($discount / 100 * $total);
        $data  = [
            'totalrp' => format_uang($total),
            'total_pay' => $total_pay,
            'total_pay_rp' => format_uang($total_pay),
            'number_generate' => ucwords(terbilang($total_pay). ' Pesos')
        ];

        return response()->json($data);
    }
}
