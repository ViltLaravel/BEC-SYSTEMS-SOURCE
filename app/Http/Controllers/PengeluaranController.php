<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PengeluaranController extends Controller
{
    // show the expenses page
    public function index()
    {
        return view('pengeluaran.index');
    }

    // displaying all expenses list
    public function data()
    {
        $expenses = Pengeluaran::orderBy('id_pengeluaran', 'desc')->get();

        return datatables()
            ->of($expenses)
            ->addIndexColumn()
            ->addColumn('created_at', function ($expenses) {
                return tanggal_indonesia($expenses->created_at, false);
            })
            ->addColumn('nominal', function ($expenses) {
                return format_uang($expenses->nominal);
            })
            ->addColumn('aksi', function ($expenses) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('pengeluaran.update', $expenses->id_pengeluaran) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('pengeluaran.destroy', $expenses->id_pengeluaran) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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

    // store the new expenses
    public function store(Request $request)
    {
        try {
            $expenses = Pengeluaran::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Expenses added successfully!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding this expenses!'
            ], 500);
        }
    }

    // show specific expenses
    public function show($id)
    {
        try {
            $expenses = Pengeluaran::find($id);
            return response()->json($expenses);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Expenses not found!'
            ], 404);
        }
    }

    public function edit($id)
    {
        //
    }

    // update the specific expenses
    public function update(Request $request, $id)
    {
        try {
            $expenses = Pengeluaran::find($id)->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Expenses updated successfully!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating this expenses!'
            ], 500);
        }
    }

    // delete the specific expenses
    public function destroy($id)
    {
        try {
            $expenses = Pengeluaran::find($id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Expenses deleted successfully!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting this expenses!'
            ], 500);
        }
    }
}
