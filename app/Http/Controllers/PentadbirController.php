<?php
namespace App\Http\Controllers;

use App\Models\Pentadbir;
use Illuminate\Http\Request;

class PentadbirController extends Controller
{
    public function index()
    {
        $data = Pentadbir::all();
        return view('pentadbir.index', compact('data'));
    }

    public function create()
    {
        return view('pentadbir.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pentadbirs,email',
        ]);

        Pentadbir::create($request->all());

        return redirect()->route('pentadbir.index')
            ->with('success', 'Pentadbir berjaya ditambah!');
    }

    public function show($id)
    {
        $item = Pentadbir::findOrFail($id);
        return view('pentadbir.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Pentadbir::findOrFail($id);
        return view('pentadbir.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Pentadbir::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pentadbirs,email,' . $id,
        ]);

        $item->update($request->all());

        return redirect()->route('pentadbir.index')
            ->with('success', 'Rekod pentadbir berjaya dikemaskini!');
    }

    public function destroy($id)
    {
        Pentadbir::findOrFail($id)->delete();

        return redirect()->route('pentadbir.index')
            ->with('success', 'Pentadbir berjaya dipadam!');
    }
}
