<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = Laporan::all();
        return view('laporan.index', compact('rows'));
    }

    public function create()
    {
        return view('laporan.create');
    }

    public function store(Request $r)
    {
        Laporan::create($r->all());
        return redirect()->route('laporan.index');
    }

    public function edit($id)
    {
        $row = Laporan::find($id);
        return view('laporan.edit', compact('row'));
    }

    public function update(Request $r, $id)
    {
        Laporan::find($id)->update($r->all());
        return redirect()->route('laporan.index');
    }

    public function destroy($id)
    {
        Laporan::find($id)->delete();
        return back();
    }
}
