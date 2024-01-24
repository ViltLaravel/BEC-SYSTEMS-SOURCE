<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;
class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = Supplier::orderBy('nama')->get();

        return view('purchase.index', compact('supplier'));
    }

      // show all purchases
      public function data()
      {
          try {
              $purchase = Purchase::orderBy('id_purchase', 'desc')->get();
              return datatables()
                  ->of($purchase)
                  ->addIndexColumn()
                  ->addColumn('total_item', function ($purchase) {
                      return ($purchase->total_item);
                  })
                  ->addColumn('total_price', function ($purchase) {
                      return '₱ '. format_uang($purchase->total_price);
                  })
                  ->addColumn('total_pay', function ($purchase) {
                      return '₱ '. format_uang($purchase->total_pay);
                  })
                  ->addColumn('date', function ($purchase) {
                      return tanggal_indonesia($purchase->created_at, false);
                  })
                  ->addColumn('supplier', function ($purchase) {
                      return $purchase->supplier->nama;
                  })
                  ->editColumn('discount', function ($purchase) {
                      return $purchase->discount . '%';
                  })
                  ->addColumn('action', function ($purchase) {
                      return '
                      <div class="btn-group">
                          <button onclick="showDetail(`'. route('purchase.show', $purchase->id_purchase) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-eye"></i></button>
                          <button onclick="deleteData(`'. route('purchase.destroy', $purchase->id_purchase) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                      </div>
                      ';
                  })
                  ->rawColumns(['action'])
                  ->make(true);
          } catch (\Throwable $th) {
              return response()->json([
                  'status' => 'error',
                  'message' => 'Error displaying the purchase'
              ], 500);
          }
      }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        try {
            $purchase = new Purchase();
            $purchase->id_supplier = $id;
            $purchase->total_item  = 0;
            $purchase->total_price = 0;
            $purchase->discount    = 0;
            $purchase->total_pay   = 0;
            $purchase->save();

            session(['id_purchase' => $purchase->id_purchase]);
            session(['id_supplier' => $purchase->id_supplier]);

            return redirect()->route('purchase_detail.index');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error accessing the page!'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
        try {
            $purchase = Purchase::findOrFail($request->id_purchase);
            $purchase->total_item = $request->total_item;
            $purchase->total_price = $request->total;
            $purchase->discount = $request->discount;
            $purchase->total_pay = $request->total_pay;
            $purchase->update();

            $detail = PurchaseDetails::where('id_purchase', $purchase->id_purchase)->get();
            foreach ($detail as $item) {
                $product = Product::find($item->id_product);
                $product->stock += $item->stock;
                $product->update();
            }

            $message = 'Purchase added successfully.';
            Session::flash('sweetAlertMessage', $message);
            Session::flash('showSweetAlert', true);
            Session::flash('sweetAlertIcon', 'success');
            Session::flash('sweetAlertTitle', 'Success');

            return redirect()->route('purchase.index')->withInput();

        } catch (\Throwable $th) {
            $message = 'Error adding this purchase!';
            Session::flash('sweetAlertMessage', $message);
            Session::flash('showSweetAlert', true);
            Session::flash('sweetAlertIcon', 'error');
            Session::flash('sweetAlertTitle', 'Error');

            return redirect()->route('purchase.index')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // show the specific purchase
    public function show($id)
    {
        try {
            $detail = PurchaseDetails::with('product')->where('id_purchase', $id)->get();

            return datatables()
                ->of($detail)
                ->addIndexColumn()
                ->addColumn('code_product', function ($detail) {
                    return '<span class="label label-success">'. $detail->product->code_product .'</span>';
                })
                ->addColumn('name_product', function ($detail) {
                    return $detail->product->name_product;
                })
                ->addColumn('purchase_price', function ($detail) {
                    return '₱ '. format_uang($detail->purchase_price);
                })
                ->addColumn('stock', function ($detail) {
                    return ($detail->stock);
                })
                ->addColumn('subtotal', function ($detail) {
                    return '₱ '. format_uang($detail->subtotal);
                })
                ->rawColumns(['code_product'])
                ->make(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase not found!'
            ], 404);
        }
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
        //
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
            $purchase = Purchase::findOrFail($id);
            $detail = PurchaseDetails::where('id_purchase', $purchase->id_purchase)->get();

            foreach ($detail as $item) {
                $product = Product::find($item->id_product);
                    if ($product) {
                        $product->stock -= $item->stock;
                        $product->update();
                    }
                    $item->delete();
            }
            $purchase->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Purchase successfully deleted.'
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Resource not found.',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting purchase details.',
            ], 500);
        }
    }
}
