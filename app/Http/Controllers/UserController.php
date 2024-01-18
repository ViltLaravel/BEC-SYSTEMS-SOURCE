<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // showing the user page
    public function index()
    {
        return view('user.index');
    }

    // display user data
    public function data()
    {
        $user = User::isNotAdmin()->orderBy('id', 'desc')->get();

        return datatables()
            ->of($user)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('user.update', $user->id) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('user.destroy', $user->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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

    // store new user
    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->level = 2;
            $user->foto = '/img/user.png';
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User added successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding user!'
            ], 500);
        }
    }

    // show specific user
    public function show($id)
    {
        try {
            $user = User::find($id);
            return response()->json($user);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!'
            ], 404);
        }
    }

    public function edit($id)
    {
        //
    }

    // update the user's details
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->has('password') && $request->password != "")
                $user->password = bcrypt($request->password);
            $user->update();

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating this user!'
            ], 500);
        }
    }

    // delete specific user
    public function destroy($id)
    {
        try {
            $user = User::find($id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting user!'
            ], 500);
        }
    }

    // view profile page
    public function profil()
    {
        $profil = auth()->user();
        return view('user.profil', compact('profil'));
    }

    // update user profile
    public function updateProfil(Request $request)
    {
        try {
            $user = auth()->user();

            $user->name = $request->name;
            if ($request->has('password') && $request->password != "") {
                if (Hash::check($request->old_password, $user->password)) {
                    if ($request->password == $request->password_confirmation) {
                        $user->password = bcrypt($request->password);
                    } else {
                        return response()->json('Confirm password is incorrect!', 422);
                    }
                } else {
                    return response()->json('Old password is incorrect!', 422);
                }
            }

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $nama = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('/img'), $nama);

                $user->foto = "/img/$nama";
            }

            $user->update();

            return response()->json([
                'title' => 'Success',
                'message' => 'Profile updated successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json('Error saving profile!', 500);
        }
    }
}
