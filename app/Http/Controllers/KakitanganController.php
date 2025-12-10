<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kakitangan;

class KakitanganController extends Controller
{
    public function index()
    {
        $rows = Kakitangan::all();
        return view('kakitangan.index', compact('rows'));
    }

    public function create()
    {
        return view('kakitangan.create');
    }

    public function store(Request $r)
    {
        Kakitangan::create($r->all());
        return redirect()->route('kakitangan.index');
    }

    public function edit($id)
    {
        $row = Kakitangan::find($id);
        return view('kakitangan.edit', compact('row'));
    }

    public function update(Request $r, $id)
    {
        Kakitangan::find($id)->update($r->all());
        return redirect()->route('kakitangan.index');
    }

    public function destroy($id)
    {
        Kakitangan::find($id)->delete();
        return back();
    }
}
