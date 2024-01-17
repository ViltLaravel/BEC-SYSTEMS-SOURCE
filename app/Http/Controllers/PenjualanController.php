<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class PenjualanController extends Controller
{
    // show the sales page
    public function index()
    {
        return view('penjualan.index');
    }

    // show all sales
    public function data()
    {
        $sales = Penjualan::with('member')->orderBy('id_penjualan', 'desc')->get();

        return datatables()
            ->of($sales)
            ->addIndexColumn()
            ->addColumn('total_item', function ($sales) {
                return ($sales->total_item);
            })
            ->addColumn('total_harga', function ($sales) {
                return '₱ '. format_uang($sales->total_harga);
            })
            ->addColumn('bayar', function ($sales) {
                return '₱ '. format_uang($sales->bayar);
            })
            ->addColumn('tanggal', function ($sales) {
                return tanggal_indonesia($sales->created_at, false);
            })
            ->addColumn('kode_member', function ($sales) {
                $member = $sales->member->kode_member ?? '';
                return '<span class="label label-success">'. $member .'</spa>';
            })
            ->editColumn('diskon', function ($sales) {
                return $sales->diskon . '%';
            })
            ->editColumn('kasir', function ($sales) {
                return $sales->user->name ?? '';
            })
            ->addColumn('aksi', function ($sales) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('penjualan.show', $sales->id_penjualan) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $sales->id_penjualan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan->id_member = $request->id_member;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();

            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
        }

        return redirect()->route('transaksi.selesai');
    }

    // show the specific sales details
    public function show($id)
    {
        try {
            $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

            return datatables()
                ->of($detail)
                ->addIndexColumn()
                ->addColumn('kode_produk', function ($detail) {
                    return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
                })
                ->addColumn('nama_produk', function ($detail) {
                    return $detail->produk->nama_produk;
                })
                ->addColumn('harga_jual', function ($detail) {
                    return '₱ '. format_uang($detail->harga_jual);
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
                'message' => 'Sales not found!'
            ], 404);
        }
    }

    // delete specific sales
    public function destroy($id)
    {
      try {
        $sales = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $sales->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
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

    public function selesai()
    {
        $setting = Setting::first();

        return view('penjualan.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaction-'. date('Y-m-d-his') .'.pdf');
    }
}