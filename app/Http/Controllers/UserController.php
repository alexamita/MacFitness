<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager loading the 'gym' and 'role' relationships
        $users = User::with(['gym', 'role'])->get();
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phoneNumber' => 'nullable|string',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|string',
            'gym_id' => 'nullable|string',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make('Qwerty1234');
        $user->role_id = $validated['role_id'];
        $user->phoneNumber = $validated['phoneNumber'];
        $user->gender = $validated['gender'];
        $user->date_of_birth = $validated['date_of_birth'];
        $user->gym_id = $validated['gym_id'];
        $user->is_active = true; //to delete later after email verification

        $user->save();

        return response()->json(['message' => 'Role Saved Successfully.'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Find the user first so we can use their ID in validation
        $user = User::findOrFail($id);

        // 2. VALIDATION: Add the ID to the unique rule to ignore the current user
        $validated = $request->validate([
            'name' => 'nullable|string',
            // The ',email,'.$id tells Laravel: "Unique in users table, except for this user's ID"
            'email' => 'nullable|email|unique:users,email,' . $id,
            'phoneNumber' => 'nullable|string',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|string',
            'gym_id' => 'nullable',
            'role_id' => 'nullable|integer|exists:roles,id',
        ]);

        // 3. Mapping the validated data to model
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];
        $user->phoneNumber = $validated['phoneNumber'];
        $user->gender = $validated['gender'];
        $user->date_of_birth = $validated['date_of_birth'];
        $user->gym_id = $validated['gym_id'];

        // Safety check: Only change password if absolutely need to reset it here
        // $user->password = Hash::make('Qwerty1234');

        $user->is_active = true; //to delete later after email verification

        $user->save();

        return response()->json(['message' => 'User updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
