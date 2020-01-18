<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Create a new model instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:255'],
            'c_password' => ['required', 'same:password'],
            'nickname' => ['nullable', 'string', 'min:3', 'max:255'],
            'role_id' => ['nullable', 'integer', Rule::exists((new Role())->getTable(), 'id')],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();

        if (empty($input['role_id'])) {
            $input = array_merge(
                $request->all(),
                ['role_id' => Role::where('name', 'user')->first()->id] // TODO: fix hardcode
            );
        }

        $user = User::create($input);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user, 200);
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
        /** @var User $user */
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'password' => ['required', 'min:8', 'max:255'],
            'c_password' => ['required', 'same:password'],
            'nickname' => ['nullable', 'string', 'min:3', 'max:255'],
            'role_id' => ['nullable', 'integer', Rule::exists((new Role())->getTable(), 'id')],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();

        $user->update($input);

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json('User deleted successfully', 200);
    }
}
