<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    // view the supplier page
    public function index()
    {
        return view('supplier.index');
    }

    // view all the list of supplier
    public function data()
    {
        $supplier = Supplier::orderBy('id_supplier', 'desc')->get();

        return datatables()
            ->of($supplier)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('supplier.update', $supplier->id_supplier) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('supplier.destroy', $supplier->id_supplier) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        //
    }

    // store new supplier
    public function store(Request $request)
    {
        try {
            $supplier = Supplier::create($request->all());

            return response()->json([
                'status'  => 'success',
                'message' => 'Supplier added successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error adding supplier!',
            ], 500);
        }
    }

    // show specific supplier
    public function show($id)
    {
        $supplier = Supplier::find($id);

        if ($supplier) {
            return response()->json($supplier);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Supplier not found!',
        ], 404);
    }

    public function edit($id)
    {
        //
    }

    // update the specific supplier
    public function update(Request $request, $id)
    {
        try {
            $supplier = Supplier::find($id)->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating this supplier!'
            ], 500);
        }
    }

    // delete specific supplier
    public function destroy($id)
    {
        try {
            $supplier = Supplier::find($id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting this supplier!'
            ], 500);
        }
    }
}
