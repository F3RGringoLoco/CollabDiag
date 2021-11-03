<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Nivel2;

class Nivel2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $nivel2 = Nivel2::where('user_id', Auth::id())->get();

        return view('nivel2.index', compact('nivel2'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('nivel2.create');
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
            'title' => 'required|unique:nivel2s,title',
        ]);

        $nivel2 = new Nivel2();
        $nivel2-> title = $request->input('title');
        $nivel2-> title_slug = Str::slug($request->input('title').Str::random(5));
        $nivel2-> user_id = Auth::id();
        $nivel2-> author_name = Auth::user()->name;
        $nivel2->save();

        return redirect()->route('nivel2.index')->with('success', 'Nueva Sesión Creada');
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
        $nivel2 = Nivel2::findOrFail($id);
        return view('nivel2.edit', compact('nivel2'));
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
        $nivel2 = Nivel2::findOrFail($id);
        $nivel2->delete();

        return redirect()->route('nivel2.index')->with('success', 'Diagrama Eliminado');
    }
}
