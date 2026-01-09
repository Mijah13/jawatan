<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GajiPokok;
use App\Models\ElaunDapat;
use App\Models\Elaun;
use Illuminate\Support\Facades\Auth;

class GajiController extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;
        $gaji = GajiPokok::where('idkakitangan', $id)->first();
        $elaun = ElaunDapat::with('elaunRelation')->where('idkakitangan', $id)->get();

        return view('kakitangan.gaji.index', compact('gaji', 'elaun'));
    }

    public function createGaji()
    {
        return view('kakitangan.gaji.create');
    }

    public function storeGaji(Request $request)
    {
        $request->validate([
            'no_gaji' => 'required|string',
            'gaji' => 'required|numeric',
            'gred_gaji' => 'required|string',
        ]);

        GajiPokok::create([
            'idkakitangan' => Auth::user()->id,
            'no_gaji' => $request->no_gaji,
            'gaji_pokok' => $request->gaji,
            'gred_gaji' => $request->gred_gaji,
        ]);

        return redirect()->route('gaji.index')->with('success', 'Maklumat gaji berjaya ditambah.');
    }

    public function createElaun()
    {
        $elaun = Elaun::orderBy('nama', 'asc')->get();
        return view('kakitangan.gaji.elaun_create', compact('elaun'));
    }

    public function storeElaun(Request $request)
    {
        $request->validate([
            'elaun' => 'required|integer',
            'nilai' => 'required|numeric',
        ]);

        ElaunDapat::create([
            'idkakitangan' => Auth::user()->id,
            'elaun' => $request->elaun,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('gaji.index')->with('success', 'Elaun berjaya ditambah.');
    }

    public function editElaun(Request $request)
    {
        $elaun = ElaunDapat::where('id', $request->id)->first();

        $elaunList = Elaun::orderBy('nama', 'asc')->get();
        return view('kakitangan.gaji.elaun_edit', compact('elaun', 'elaunList'));
    }
    public function updateElaun(Request $request)
    {
        $elaunRecord = ElaunDapat::where('id', $request->id)->firstOrFail();

        $request->validate([
            'elaun' => 'required|integer',
            'nilai' => 'required|numeric',
        ]);

        $elaunRecord->update([
            'elaun' => $request->elaun,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('gaji.index')->with('success', 'Elaun berjaya diubah.');
    }

    public function destroyELaun(Request $request)
    {
        $elaunRecord = ElaunDapat::where('id', $request->id)->firstOrFail();

        $elaunRecord->delete();

        return redirect()->route('gaji.index')->with('success', 'Elaun berjaya dihapus.');
    }
    public function editGaji($id)
    {
        $gaji = GajiPokok::where('idkakitangan', $id)->first();
        return view('kakitangan.gaji.gaji_edit', compact('gaji'));
    }
    public function updateGaji(Request $request, $id)
    {
        // $id passed here is idkakitangan
        $gajiRecord = GajiPokok::where('idkakitangan', $id)->firstOrFail();

        $request->validate([
            'no_gaji' => 'required|string',
            'gaji_pokok' => 'required',
            'gred_gaji' => 'required|string',
        ]);
        $gajiRecord->update([
            'no_gaji' => $request->no_gaji,
            'gaji_pokok' => $request->gaji_pokok,
            'gred_gaji' => $request->gred_gaji,
        ]);

        return redirect()->route('gaji.index')->with('success', 'Maklumat gaji berjaya diubah.');
    }

}
