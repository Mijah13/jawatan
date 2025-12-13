<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kakitangan;
use App\Models\TarafPerkhidmatan;
use Illuminate\Support\Facades\Auth;

class TarafPerkhidmatanController extends Controller
{
    public function edit()
    {
        // dd('Route hit', Auth::id());
        $kakitangan = Auth::user();
        $taraf = TarafPerkhidmatan::orderBy('taraf', 'asc')->get();

        return view('kakitangan.taraf.edit', compact('kakitangan', 'taraf'));

    }

    public function update(Request $request)
    {
        $request->validate([
            'taraf' => 'required|integer',
        ]);

        $kakitangan = Auth::user();

        // Assuming 'taraf_perkhidmatan' is the column name in kakitangan table based on legacy code
        // Need to verify if this column exists in Kakitangan model fillable
        $kakitangan->taraf_perkhidmatan = $request->taraf;
        $kakitangan->save();

        return redirect()->route('surat.index')->with('success', 'Taraf perkhidmatan berjaya dikemaskini.');
    }
}
