<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use PDF;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all()->pluck('name_category', 'id_category');
        $unit = Unit::all()->pluck('name_unit', 'id_unit');

        return view('product.index', compact('category', 'unit'));
    }

    // show all product
    public function data()
    {
        $product = Product::leftJoin('categories', 'categories.id_category', 'products.id_category')
        ->leftJoin('units', 'units.id_unit', 'products.id_unit')
        ->select('products.*', 'name_category', 'name_unit')
        ->get();

        return datatables()
            ->of($product)
            ->addIndexColumn()
            ->addColumn('select_all', function ($product) {
                return '
                    <input type="checkbox" name="id_product[]" value="'. $product->id_product .'">
                ';
            })
            ->addColumn('code_product', function ($product) {
                return '<span class="label label-success">'. $product->code_product .'</span>';
            })
            ->addColumn('price_purchase', function ($product) {
                return format_uang($product->price_purchase);
            })
            ->addColumn('price_selling', function ($product) {
                return format_uang($product->price_selling);
            })
            ->addColumn('stock', function ($product) {
                return ($product->stock);
            })
            ->addColumn('action', function ($product) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('product.update', $product->id_product) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('product.destroy', $product->id_product) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'code_product', 'select_all'])
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
        $product = Product::latest()->first() ?? new Product();

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found!'
            ], 404);
        }

        $request['code_product'] = 'P'. tambah_nol_didepan((int)$product->id_product +1, 6);

        try { $product = Product::create($request->all());
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found!'
            ], 404);
        } else {
            return response()->json($product);
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
        $product = Product::find($id);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
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
        foreach ($request->id_product as $id) {
            $product = Product::find($id);
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
    public function barcode(Request $request)
    {
        $dataproduct = array();
        foreach ($request->id_product as $id) {
            $product = Product::find($id);
            $dataproduct[] = $product;
        }

        $no  = 1;
        $pdf = PDF::loadView('product.barcode', compact('dataproduct', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('product.pdf');
    }
}
