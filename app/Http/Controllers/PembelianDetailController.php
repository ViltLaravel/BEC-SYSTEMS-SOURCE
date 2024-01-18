<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    // show and display the data in the page
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        $produk = Produk::orderBy('nama_produk')->get();
        $supplier = Supplier::find(session('id_supplier'));
        $diskon = Pembelian::find($id_pembelian)->diskon ?? 0;

        if (! $supplier) {
            abort(404);
        }

        return view('pembelian_detail.index', compact('id_pembelian', 'produk', 'supplier', 'diskon'));
    }

    // displaying the product in the datatable
    public function data($id)
    {
        $detail = PembelianDetail::with('produk')
            ->where('id_pembelian', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_beli']  = '₱ '.format_uang ($item->harga_beli);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_pembelian_detail .'" value="'. $item->jumlah .'">';
            $row['subtotal']    = '₱ '.format_uang ($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('pembelian_detail.destroy', $item->id_pembelian_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_beli * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'harga_beli'  => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->make(true);
    }

    // store product
    public function store(Request $request)
    {
        $product = Produk::where('id_produk', $request->id_produk)->first();
        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching the product!'
            ], 500);
        }

        $detail = new PembelianDetail();
        $detail->id_pembelian = $request->id_pembelian;
        $detail->id_produk = $product->id_produk;
        $detail->harga_beli = $product->harga_beli;
        $detail->jumlah = 1;
        $detail->subtotal = $product->harga_beli;
        $detail->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product added successfully'
        ], 200);
    }

    // update the purchase
    public function update(Request $request, $id)
    {
        $detail = PembelianDetail::findOrFail($id);

        $detail->update([
            'jumlah' => $request->jumlah,
            'subtotal' => $detail->harga_beli * $request->jumlah,
        ]);

        return response()->json('Purchase updated successfully', 200);
    }

    // delete the product added in the datatable
    public function destroy($id)
    {
        $product_detail = PembelianDetail::find($id);
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

    public function loadForm($diskon, $total)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $data  = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Pesos')
        ];

        return response()->json($data);
    }
}
