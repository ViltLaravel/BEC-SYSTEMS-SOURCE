<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sales;
use App\Models\SalesDetails;
use App\Models\Product;
use App\Models\Setting;
use PDF;
use Illuminate\Support\Facades\Session;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sales.index');
    }

     // show all sales
     public function data()
     {
         $sales = Sales::with('branch')->orderBy('id_sales', 'desc')->get();

         return datatables()
             ->of($sales)
             ->addIndexColumn()
             ->addColumn('total_item', function ($sales) {
                 return ($sales->total_item);
             })
             ->addColumn('total_price', function ($sales) {
                 return '₱ '. format_uang($sales->total_price);
             })
             ->addColumn('total_pay', function ($sales) {
                 return '₱ '. format_uang($sales->total_pay);
             })
             ->addColumn('date', function ($sales) {
                 return tanggal_indonesia($sales->created_at, false);
             })
             ->addColumn('code_branch', function ($sales) {
                 $branch = $sales->branch->code_branch ?? '';
                 return '<span class="label label-success">'. $branch .'</spa>';
             })
             ->editColumn('cashier', function ($sales) {
                 return $sales->user->name ?? '';
             })
             ->addColumn('action', function ($sales) {
                 return '
                 <div class="btn-group">
                     <button onclick="showDetail(`'. route('sales.show', $sales->id_sales) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-eye"></i></button>
                     <button onclick="deleteData(`'. route('sales.destroy', $sales->id_sales) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                 </div>
                 ';
             })
             ->rawColumns(['action', 'code_branch'])
             ->make(true);
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $sales = new Sales();
            $sales->id_branch = null;
            $sales->total_item = 0;
            $sales->total_price = 0;
            $sales->total_pay = 0;
            $sales->change = 0;
            $sales->id_user = auth()->id();
            $sales->save();

            session(['id_sales' => $sales->id_sales]);

            return redirect()->route('transaction_details.index');

        } catch (\Throwable $th) {
            $message = 'Error archieving latest sales!';
            Session::flash('sweetAlertMessage', $message);
            Session::flash('showSweetAlert', true);
            Session::flash('sweetAlertIcon', 'error');
            Session::flash('sweetAlertTitle', 'error');

            return redirect()->route('transaction_details.index')->withInput();
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
            $sales = Sales::findOrFail($request->id_sales);
            $sales->id_branch = $request->id_branch;
            $sales->total_item = $request->total_item;
            $sales->total_price = $request->total;
            $sales->total_pay = $request->total_pay;
            $sales->change = $request->change;
            $sales->update();

            $detail = SalesDetails::where('id_sales', $sales->id_sales)->get();
            foreach ($detail as $item) {
                $product = Product::find($item->id_product);
                $product->stock -= $item->stock;
                $product->update();
            }
            $message = 'Sales added successfully.';
            Session::flash('sweetAlertMessage', $message);
            Session::flash('showSweetAlert', true);
            Session::flash('sweetAlertIcon', 'success');
            Session::flash('sweetAlertTitle', 'Success');

            return redirect()->route('transaksi.selesai')->withInput();

        } catch (\Throwable $th) {
            $message = 'Error saving this sales!';
            Session::flash('sweetAlertMessage', $message);
            Session::flash('showSweetAlert', true);
            Session::flash('sweetAlertIcon', 'error');
            Session::flash('sweetAlertTitle', 'error');

            return redirect()->route('transaksi.selesai')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $detail = SalesDetails::with('product')->where('id_sales', $id)->get();

            return datatables()
                ->of($detail)
                ->addIndexColumn()
                ->addColumn('code_product', function ($detail) {
                    return '<span class="label label-success">'. $detail->product->code_product .'</span>';
                })
                ->addColumn('name_product', function ($detail) {
                    return $detail->product->name_product;
                })
                ->addColumn('selling_price', function ($detail) {
                    return '₱ '. format_uang($detail->selling_price);
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
                'message' => 'Sales not found!'
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
            $sales = Sales::find($id);
            $detail    = SalesDetails::where('id_sales', $sales->id_sales)->get();
            foreach ($detail as $item) {
                $product = Product::find($item->id_product);
                if ($product) {
                    $product->stock += $item->stock;
                    $product->update();
                }
                $item->delete();
            }
            $sales->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Sales deleted successfully.'
                ], 200);
          } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting this sales!'
            ], 500);
          }
    }

     // settings in customizing the layouts
     public function sales()
     {
         $setting = Setting::first();

         return view('sales.sales', compact('setting'));
     }

     // format in small PDF
     public function smallFormat()
     {
         $setting = Setting::first();
         $sales = Sales::find(session('id_sales'));
         if (! $sales) {
             abort(404);
         }
         $detail = SalesDetails::with('product')
             ->where('id_sales', session('id_sales'))
             ->get();

         return view('sales.smallFormat', compact('setting', 'sales', 'detail'));
     }

     // format in PDF
     public function pdfFormat()
     {
         $setting = Setting::first();
         $sales = Sales::find(session('id_sales'));
         if (! $sales) {
             abort(404);
         }
         $detail = SalesDetails::with('product')
             ->where('id_sales', session('id_sales'))
             ->get();

         $pdf = PDF::loadView('sales.pdfFormat', compact('setting', 'sales', 'detail'));
         $pdf->setPaper(0,0,609,440, 'potrait');
         return $pdf->stream('Transaction-'. date('Y-m-d-his') .'.pdf');
     }
}
