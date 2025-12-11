<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kakitangan;

class KakitanganController extends Controller
{
    public function index()
    {
        $id = auth()->user()->id;
        // $rows = Kakitangan::find($id);
         $kakitangan = Kakitangan::with([
            'apc' => fn($q) => $q->orderBy('tahunterima', 'desc'),
            'pencapaian' => fn($q) => $q->orderBy('tarikhpencapaian', 'desc')->with('peringkatSumbangan'),
            'pingat' => fn($q) => $q->orderBy('kemaskini', 'asc'),
            'harta' => fn($q) => $q->orderBy('tarikhisytihar', 'desc')->with('jenisIsytihar')
        ])->where('id', $id)->firstOrFail();
        return view('kakitangan.index', compact('kakitangan'));
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
