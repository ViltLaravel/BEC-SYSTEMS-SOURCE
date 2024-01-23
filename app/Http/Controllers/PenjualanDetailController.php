<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    // show the data in the sales transaction page
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        $member = Member::orderBy('nama')->get();
        $diskon = Setting::first()->diskon ?? 0;

        // Check whether there are any transactions in progress
        if ($id_penjualan = session('id_penjualan')) {
            $penjualan = Penjualan::find($id_penjualan);
            $memberSelected = $penjualan->member ?? new Member();

            return view('penjualan_detail.index', compact('produk', 'member', 'diskon', 'id_penjualan', 'penjualan', 'memberSelected'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('home');
            }
        }
    }

    // display the selected product in the datatable
    public function data($id)
    {
        try {
            $detail = PenjualanDetail::with('produk')
                ->where('id_penjualan', $id)
                ->get();

            $data = [];
            $total = 0;
            $total_item = 0;

            foreach ($detail as $item) {
                $row = [
                    'kode_produk' => '<span class="label label-success">' . $item->produk['kode_produk'] . '</span',
                    'nama_produk' => $item->produk['nama_produk'],
                    'harga_jual'  => '₱ ' . format_uang($item->harga_jual),
                    'jumlah'      => '<span class="quantity-input" data-id="' . $item->id_penjualan_detail . '">
                                        <input type="number" class="form-control input-sm quantity" value="' . $item->jumlah . '">
                                    </span>',
                    'diskon'      => $item->diskon . '%',
                    'subtotal'    => '₱ ' . format_uang($item->subtotal),
                    'aksi'        => '<div class="btn-group">
                                        <button onclick="deleteData(`' . route('transaksi.destroy', $item->id_penjualan_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                    </div>',
                ];

                // Check if quantity exceeds stock
                if ($item->jumlah > $item->produk['stok']) {
                    // Display alert if quantity exceeds stock
                    $row['jumlah'] .= '<script>Swal.fire("Error","Quantity exceeds available stock!","error");</script>';
                }

                $data[] = $row;

                $total += $item->harga_jual * $item->jumlah - (($item->diskon * $item->jumlah) / 100 * $item->harga_jual);
                $total_item += $item->jumlah;
            }

            // Add totals to the end of the table
            $data[] = [
                'kode_produk' => '<div class="total hide">' . $total . '</div>',
                'nama_produk' => '<div class="total_item hide">' . $total_item . '</div>',
                'harga_jual'  => '',
                'jumlah'      => '',
                'diskon'      => '',
                'subtotal'    => '',
                'aksi'        => '',
            ];

            return datatables()
                ->of($data)
                ->addIndexColumn()
                ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
                ->make(true);
        } catch (\Throwable $th) {
            $message = 'Unable to display the data!';
            Session::flash('sweetAlertMessage', $message);
            Session::flash('showSweetAlert', true);
            Session::flash('sweetAlertIcon', 'error');
            Session::flash('sweetAlertTitle', 'Error');

            return redirect()->route('transaksi.baru')->withInput();
        }
    }

    // store the product that is selected and will be displayed in the data
    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();

        // Check if the product exists
        if (!$produk) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding this product!'
            ], 500);
        }

        // Check if the product is out of stock
        if ($produk->stok <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product is out of stock and cannot be added to the sale.'
            ], 400);
        }

        // Continue with the sale if the product is available
        $detail = new PenjualanDetail();
        $detail->id_penjualan = $request->id_penjualan;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_jual = $produk->harga_jual;
        $detail->jumlah = 1;
        $detail->diskon = $produk->diskon;
        $detail->subtotal = $produk->harga_jual - ($produk->diskon / 100 * $produk->harga_jual);
        $detail->save();

        // Update the product stock
        $produk->stok - 1;
        $produk->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product successfully added.'
        ], 200);
    }

    // update the sales
    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah - (($detail->diskon * $request->jumlah) / 100 * $detail->harga_jual);;
        $detail->update();
    }

    // deleted the selected product
    public function destroy($id)
    {
        try {
            $detail = PenjualanDetail::find($id);
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
    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        try {
            $data    = [
                'totalrp' => format_uang($total),
                'bayar' => $bayar,
                'bayarrp' => format_uang($bayar),
                'terbilang' => ucwords(terbilang($bayar). ' Pesos'),
                'kembalirp' => format_uang($kembali),
                'kembali_terbilang' => ucwords(terbilang($kembali). ' Pesos'),
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            $message = 'Unable to load data!';
            Session::flash('sweetAlertMessage', $message);
            Session::flash('showSweetAlert', true);
            Session::flash('sweetAlertIcon', 'error');
            Session::flash('sweetAlertTitle', 'error');

            return redirect()->route('transaksi.baru')->withInput();
        }
    }
}
