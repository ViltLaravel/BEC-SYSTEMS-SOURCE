<?php

namespace App\Http\Controllers\Branches;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Setting;
use PDF;

class BranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('branches.index');
    }

    // display all branches
    public function data()
    {
        $branch = Branch::orderBy('code_branch')->get();

        return datatables()
            ->of($branch)
            ->addIndexColumn()
            ->addColumn('select_all', function ($branch) {
                return '
                    <input type="checkbox" name="id_branch[]" value="'. $branch->id_branch .'">
                ';
            })
            ->addColumn('code_branch', function ($branch) {
                return '<span class="label label-success">'. $branch->code_branch .'<span>';
            })
            ->addColumn('action', function ($branch) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('branch.update', $branch->id_branch) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('branch.destroy', $branch->id_branch) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'select_all', 'code_branch'])
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
        try {
            $latestBranch = Branch::latest()->first();
            $code_branch = $latestBranch ? (int) $latestBranch->code_branch + 1 : 1;

            $branch = new Branch();
            $branch->code_branch = tambah_nol_didepan($code_branch, 6);
            $branch->name = $request->name;
            $branch->phone = $request->phone;
            $branch->address = $request->address;

            $branch->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Branch successfully added!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding branch!',
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
        $branch = Branch::find($id);
        if (!$branch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found!'
            ], 404);
        }
        else {
            return response()->json($branch);
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
        try {
            $branch = Branch::findOrFail($id);
            $branch->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Branch updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating branch!'
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
        try {
            $branch = Branch::findOrFail($id);
            $branch->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Branch deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting this branch!'
            ], 500);
        }
    }

    // generate card of branches
    public function generateBranch(Request $request)
    {
        $databranch = collect(array());
        foreach ($request->id_branch as $id) {
            $branch = Branch::find($id);
            $databranch[] = $branch;
        }

        $databranch = $databranch->chunk(2);
        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('branches.card', compact('databranch', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('member.pdf');
    }
}
