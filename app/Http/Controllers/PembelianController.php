<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;

class PembelianController extends Controller
{
    // show purchase page
    public function index()
    {
        $supplier = Supplier::orderBy('nama')->get();

        return view('pembelian.index', compact('supplier'));
    }

    // show all purchases
    public function data()
    {
        try {
            $purchase = Pembelian::orderBy('id_pembelian', 'desc')->get();
            return datatables()
                ->of($purchase)
                ->addIndexColumn()
                ->addColumn('total_item', function ($purchase) {
                    return ($purchase->total_item);
                })
                ->addColumn('total_harga', function ($purchase) {
                    return '₱ '. format_uang($purchase->total_harga);
                })
                ->addColumn('bayar', function ($purchase) {
                    return '₱ '. format_uang($purchase->bayar);
                })
                ->addColumn('tanggal', function ($purchase) {
                    return tanggal_indonesia($purchase->created_at, false);
                })
                ->addColumn('supplier', function ($purchase) {
                    if ($purchase->supplier && $purchase->supplier->nama !== null) {
                        return $purchase->supplier->nama;
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('diskon', function ($purchase) {
                    return $purchase->diskon . '%';
                })
                ->addColumn('aksi', function ($purchase) {
                    return '
                    <div class="btn-group">
                        <button onclick="showDetail(`'. route('purchase.show', $purchase->id_pembelian) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-eye"></i></button>
                        <button onclick="deleteData(`'. route('purchase.destroy', $purchase->id_pembelian) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error displaying the purchase'
            ], 500);
        }
    }

    // show the archieve purchase
    public function create($id)
    {
        try {
            $purchase = new Pembelian();
            $purchase->id_supplier = $id;
            $purchase->total_item  = 0;
            $purchase->total_harga = 0;
            $purchase->diskon      = 0;
            $purchase->bayar       = 0;
            $purchase->save();

            session(['id_pembelian' => $purchase->id_pembelian]);
            session(['id_supplier' => $purchase->id_supplier]);

            return redirect()->route('purchase_detail.index');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error accessing the page!'
            ], 404);
        }
    }


    // storing new purchase
    public function store(Request $request)
    {
        try {
            $purchase = Pembelian::findOrFail($request->id_pembelian);
            $purchase->total_item = $request->total_item;
            $purchase->total_harga = $request->total;
            $purchase->diskon = $request->diskon;
            $purchase->bayar = $request->bayar;
            $purchase->update();

            $detail = PembelianDetail::where('id_pembelian', $purchase->id_pembelian)->get();
            foreach ($detail as $item) {
                $product = Produk::find($item->id_produk);
                $product->stok += $item->jumlah;
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

    // show the specific purchase
    public function show($id)
    {
        try {
            $detail = PembelianDetail::with('produk')->where('id_pembelian', $id)->get();

            return datatables()
                ->of($detail)
                ->addIndexColumn()
                ->addColumn('kode_produk', function ($detail) {
                    return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
                })
                ->addColumn('nama_produk', function ($detail) {
                    return $detail->produk->nama_produk;
                })
                ->addColumn('harga_beli', function ($detail) {
                    return '₱ '. format_uang($detail->harga_beli);
                })
                ->addColumn('jumlah', function ($detail) {
                    return ($detail->jumlah);
                })
                ->addColumn('subtotal', function ($detail) {
                    return '₱ '. format_uang($detail->subtotal);
                })
                ->rawColumns(['kode_produk'])
                ->make(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase not found!'
            ], 404);
        }
    }

    // delete the specific puchase
    public function destroy($id)
    {
        try {
            $purchase = Pembelian::findOrFail($id);
            $detail = PembelianDetail::where('id_pembelian', $purchase->id_pembelian)->get();

            foreach ($detail as $item) {
                $produk = Produk::find($item->id_produk);
                    if ($produk) {
                        $produk->stok -= $item->jumlah;
                        $produk->update();
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
