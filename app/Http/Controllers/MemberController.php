<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class MemberController extends Controller
{
    // view the branch page
    public function index()
    {
        return view('member.index');
    }

    // display all branches
    public function data()
    {
        $member = Member::orderBy('kode_member')->get();

        return datatables()
            ->of($member)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_member[]" value="'. $produk->id_member .'">
                ';
            })
            ->addColumn('kode_member', function ($member) {
                return '<span class="label label-success">'. $member->kode_member .'<span>';
            })
            ->addColumn('aksi', function ($member) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('member.update', $member->id_member) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('member.destroy', $member->id_member) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_member'])
            ->make(true);
    }

    public function create()
    {
        //
    }

    // store new branch
    public function store(Request $request)
    {
        try {
            $latestMember = Member::latest()->first();
            $kode_member = $latestMember ? (int) $latestMember->kode_member + 1 : 1;

            $member = new Member();
            $member->kode_member = 'BRANCH' . tambah_nol_didepan($kode_member, 6);
            $member->nama = $request->nama;
            $member->telepon = $request->telepon;
            $member->alamat = $request->alamat;

            $member->save();

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

    // show the specific branch
    public function show($id)
    {
        $member = Member::find($id);
        if (!$member) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found!'
            ], 404);
        }
        else {
            return response()->json($member);
        }
    }

    public function edit($id)
    {
        //
    }

    // update the specific branch
    public function update(Request $request, $id)
    {
        try {
            $member = Member::findOrFail($id);
            $member->update($request->all());

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

    // delete the specific branch
    public function destroy($id)
    {
        try {
            $member = Member::findOrFail($id);
            $member->delete();

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
    public function cetakMember(Request $request)
    {
        $datamember = collect(array());
        foreach ($request->id_member as $id) {
            $member = Member::find($id);
            $datamember[] = $member;
        }

        $datamember = $datamember->chunk(2);
        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('member.cetak', compact('datamember', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('member.pdf');
    }
}
