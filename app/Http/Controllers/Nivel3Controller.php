<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Nivel3;

class Nivel3Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $nivel3 = Nivel3::where('user_id', Auth::id())->get();

        return view('nivel3.index', compact('nivel3'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('nivel3.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'title' => 'required|unique:nivel3s,title',
        ]);

        $nivel3 = new Nivel3();
        $nivel3-> title = $request->input('title');
        $nivel3-> title_slug = Str::slug($request->input('title')."-".Str::random(5));
        $nivel3-> user_id = Auth::id();
        $nivel3-> author_name = Auth::user()->name;
        $nivel3->save();

        return redirect()->route('nivel3.index')->with('success', 'Nueva Sesión Creada');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $nivel3 = Nivel3::findOrFail($id);
        return view('nivel3.edit', compact('nivel3'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $nivel3 = Nivel3::findOrFail($id);
        $nivel3->delete();

        return redirect()->route('nivel3.index')->with('success', 'Diagrama Eliminado');
    }
}
