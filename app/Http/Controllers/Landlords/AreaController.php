<?php

namespace App\Http\Controllers\Landlords;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
                
        $areas = Area::all();
        return Inertia::render('Landlords/Areas/Index', [
            'areas' => Area::with('user')
                ->join('users', 'users.id', '=', 'areas.user_id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->select('areas.id', 'areas.name as name', 'users.name as owner', 'users.email as owner_email')
                ->orderBy('areas.id')
                ->paginate(5),

        ]
        );
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
    public function store(Request $request, User $user)
    {
        if (auth()->user()->hasRole(['admin', 'owner'])) {

            $this->validate($request, [
                'name' => 'required|unique:areas',
            ]);

            Area::create([
                'name' => $request->name,
                // 'user_id' => Auth::user()->id,
            ]);

            return back();
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        if (auth()->user()->hasRole(['admin', 'owner'])) {

            $this->validate($request, [
                'name' => 'required|unique:areas',
            ]);

            $area->update(['name' => $request->name]);

            return back();
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        if (auth()->user()->hasAnyRole(['admin', 'owner'])) {
            $area->delete();
            return back();
        }
        return back();
    }
}
