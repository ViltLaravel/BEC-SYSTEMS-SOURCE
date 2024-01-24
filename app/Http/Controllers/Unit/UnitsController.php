<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('units.index');
    }

    public function data()
    {
        $units = Unit::orderBy('id_unit', 'desc')->get();

        return datatables()
            ->of($units)
            ->addIndexColumn()
            ->addColumn('action', function ($units) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('unit.update', $units->id_unit) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('unit.destroy', $units->id_unit) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
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
        $units = new Unit();
        if(!$units) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit not found!'
            ]);
        }
        $units->name_unit = $request->name_unit;
        try {
            $units->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Unit added successfully!'
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding unit!'
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
        $units = Unit::find($id);
        if (!$units) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit not found!'
            ], 404);
        }
        else {
            return response()->json($units);
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
        $units = Unit::find($id);
        $units->name_unit = $request->name_unit;

        if(!$units) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit not found!'
            ], 404);
        }

        try{ $units->update();
            return response()->json([
                'status' => 'success',
                'message' => 'Unit updated successfully!'
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating unit!'
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
        $units = Unit::find($id);

        if (!$units) {
            return response()->json([
                'status' => 'error',
                 'message' => 'Unit not found'
            ], 404);
        }

        try {
            $units->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Unit deleted successfully'
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => 'error',
                'message' => 'Error deleting unit'
            ], 500);
        }
    }
}
