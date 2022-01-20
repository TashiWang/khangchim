<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LandlordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Admins/Landlords/Index', [
            'landlords' => DB::table('users')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.updated_at')
                ->where('roles.name', 'owner')
                ->paginate(4),
        ]);
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

        if (auth()->user()->hasRole(['admin'])) {
            $this->validate($request, [
                'name' => ['required'],
                'email' => ['required', 'email'],
            ]);

            $hashed_password = Str::random(8);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make($hashed_password), // $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
                'remember_token' => Str::random(10),

            ])->assignRole(Role::where('name', 'owner')->first());

            // Prepare data for mail

            $email = $request->get('email');
            $name = $request->get('name');
            $email = $request->get('email');
            $password = $hashed_password;

            $maildata = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ];

            // send welcome mail
            Mail::to($email)->send(new WelcomeMail($maildata));

            return back();
        }
        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->hasRole(['admin'])) {
            $this->validate($request, [
                'name' => ['required', 'max:25'],
                'email' => ['required'],
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return back();
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (auth()->user()->hasRole(['admin'])) {
            $user->delete();
            return back();
        }
        return back();
    }
}
