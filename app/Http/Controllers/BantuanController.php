<?php

namespace App\Http\Controllers;

use App\Models\Bantuan;
use Illuminate\Http\Request;

class BantuanController extends Controller
{
    public function index()
    {
        $data = Bantuan::all();
        return view('bantuan.index', compact('data'));
    }

    public function create()
    {
        return view('bantuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bantuan' => 'required',
        ]);

        Bantuan::create($request->all());

        return redirect()->route('bantuan.index')
            ->with('success', 'Rekod bantuan berjaya ditambah!');
    }

    public function show($id)
    {
        $item = Bantuan::findOrFail($id);
        return view('bantuan.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Bantuan::findOrFail($id);
        return view('bantuan.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Bantuan::findOrFail($id);

        $request->validate([
            'nama_bantuan' => 'required',
        ]);

        $item->update($request->all());

        return redirect()->route('bantuan.index')
            ->with('success', 'Rekod bantuan berjaya dikemaskini!');
    }

    public function destroy($id)
    {
        Bantuan::findOrFail($id)->delete();

        return redirect()->route('bantuan.index')
            ->with('success', 'Rekod bantuan berjaya dipadam!');
    }
}
