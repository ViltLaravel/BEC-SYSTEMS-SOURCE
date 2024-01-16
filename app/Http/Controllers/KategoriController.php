<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{

    // view category
    public function index()
    {
        return view('kategori.index');
    }

    // display all category
    public function data()
    {
        $category = Kategori::orderBy('id_kategori', 'desc')->get();

        return datatables()
            ->of($category)
            ->addIndexColumn()
            ->addColumn('aksi', function ($category) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('kategori.update', $category->id_kategori) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('kategori.destroy', $category->id_kategori) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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

    // store new category
    public function store(Request $request)
    {
        $category = new Kategori();
        if(!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found!'
            ]);
        }
        $category->nama_kategori = $request->nama_kategori;
        try {$category->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Category added successfully!'
            ], 200);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding category!'
            ], 500);
        }
    }

    // show category
    public function show($id)
    {
        $category = Kategori::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found!'
            ], 404);
        }
        else {
            return response()->json($category);
        }
    }

    public function edit($id)
    {
        //
    }

    // update the specific category
    public function update(Request $request, $id)
    {
        $category = Kategori::find($id);
        $category->nama_kategori = $request->nama_kategori;

        if(!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found!'
            ], 404);
        }

        try{ $category->update();
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully!'
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating category'
            ], 500);
        }
    }

    // delete the specific category
    public function destroy($id)
    {
        $category = Kategori::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                 'message' => 'Category not found'
            ], 404);
        }

        try {
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json(['status' => 'error',
                'message' => 'Error deleting category'
            ], 500);
        }
    }
}
