<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use PDF;

class ProdukController extends Controller
{
    // show all category
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');

        return view('produk.index', compact('kategori'));
    }

    // show all product
    public function data()
    {
        $product = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')
            ->select('produk.*', 'nama_kategori')
            ->get();

        return datatables()
            ->of($product)
            ->addIndexColumn()
            ->addColumn('select_all', function ($product) {
                return '
                    <input type="checkbox" name="id_produk[]" value="'. $product->id_produk .'">
                ';
            })
            ->addColumn('kode_produk', function ($product) {
                return '<span class="label label-success">'. $product->kode_produk .'</span>';
            })
            ->addColumn('harga_beli', function ($product) {
                return format_uang($product->harga_beli);
            })
            ->addColumn('harga_jual', function ($product) {
                return format_uang($product->harga_jual);
            })
            ->addColumn('stok', function ($product) {
                return ($product->stok);
            })
            ->addColumn('aksi', function ($product) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('items.update', $product->id_produk) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('items.destroy', $product->id_produk) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all'])
            ->make(true);
    }

    public function create()
    {
        //
    }

    // store product
    public function store(Request $request)
    {
        $product = Produk::latest()->first() ?? new Produk();

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found!'
            ], 404);
        }

        $request['kode_produk'] = 'P'. tambah_nol_didepan((int)$product->id_produk +1, 6);

        try { $product = Produk::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Product successfully added!'
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding product!'
            ], 500);
        }
    }

    // show specific product
    public function show($id)
    {
        $product = Produk::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found!'
            ], 404);
        } else {
            return response()->json($product);
        }
    }

    public function edit($id)
    {
        //
    }

    // update specific product
    public function update(Request $request, $id)
    {
        $product = Produk::find($id);
        if(!$product){
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found!'
            ], 404);
        }
        try { $product->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Product successfully updated!'
            ], 200);
        }
        catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating product!'
            ], 500);
        }
    }

    // delete specific product
    public function destroy($id)
    {
        $product = Produk::find($id);
        if(!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found!'
            ], 404);
        }

        try {$product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Products successfully deleted!'
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting this product!'
            ], 500);
        }
    }

    // delete selected product
    public function deleteSelected(Request $request)
    {
        foreach ($request->id_produk as $id) {
            $product = Produk::find($id);
                if (!$product){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product not found!',
                    ], 404);
                }
                try {$product->delete();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Selected product successfully deleted!'
                    ], 200);
                }
                catch(\Exception $e){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error deleting this selected product!'
                    ], 500);
                }

        }

        return response(null, 204);
    }

    // generate product barcode
    public function cetakBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $dataproduk[] = $produk;
        }

        $no  = 1;
        $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('product.pdf');
    }
}
