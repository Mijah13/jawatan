<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // Resource methods removed as this controller is used for specific reports.

    // Laporan Methods
    public function pengisian_gred()
    {
        $rows = \App\Models\Perjawatan::select(
            'gred.gred as kodgred',
            'jawatan.jawatan',
            'perjawatan.jawatan as idjawatan',
            'perjawatan.gred as idgred',
            'perjawatan.bilanganperjawatan',
            'perjawatan.waran'
        )
            ->addSelect(\Illuminate\Support\Facades\DB::raw('(SELECT count(kakitangan.gred) FROM kakitangan LEFT JOIN penempatan ON penempatan.id = kakitangan.kodpenempatan WHERE (penempatan.kod = "A" OR penempatan.kod = "B") AND kakitangan.gred = perjawatan.gred AND kakitangan.jawatan = perjawatan.jawatan) as isi'))
            ->leftJoin('gred', 'gred.id', '=', 'perjawatan.gred')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'perjawatan.jawatan')
            ->orderBy('gred.keutamaan', 'desc')
            ->get();

        $tarikh = \App\Models\Perjawatan::max('tarikhkemaskini');

        return view('kakitangan.laporan.pengisian_gred', compact('rows', 'tarikh'));
    }
    public function teknikal()
    {
        $rows = \App\Models\Perjawatan::select(
            'gred.gred as kodgred',
            'jawatan.jawatan',
            'perjawatan.bilanganperjawatan',
            'perjawatan.waran'
        )
            ->addSelect(\Illuminate\Support\Facades\DB::raw('(SELECT count(kakitangan.id) FROM kakitangan LEFT JOIN penempatan ON penempatan.id = kakitangan.kodpenempatan WHERE kakitangan.gred = gred.id AND kakitangan.jawatan = jawatan.id AND( penempatan.kod = "A" OR penempatan.kod = "B")) as isi'))
            ->leftJoin('gred', 'gred.id', '=', 'perjawatan.gred')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'perjawatan.jawatan')
            ->where('jawatan.jawatan', 'LIKE', '%LATIHAN%')
            ->orderBy('gred.keutamaan', 'desc')
            ->get();

        $tarikh = \App\Models\Perjawatan::max('tarikhkemaskini');

        return view('kakitangan.laporan.teknikal', compact('rows', 'tarikh'));
    }
    public function lantikan()
    {
        $rows = \App\Models\Kakitangan::select(
            'kakitangan.level',
            'kakitangan.nama',
            'gred.gred',
            'jawatan.kod as kodjawatan',
            'kakitangan.mykad',
            'kakitangan.tarikhlantikanpertama',
            'kakitangan.tarikhlantikansekarang',
            'kakitangan.tarikhpengesahanjawatan',
            'kakitangan.tarikhmemangku',
            'kakitangan.tarikhnaikpangkat',
            'kakitangan.tarikhkeciast',
            'penempatan.kod as kodpenempatan'
        )
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->where(function ($query) {
                $query->where('penempatan.kod', 'A')
                    ->orWhere('penempatan.kod', 'B');
            })
            ->orderBy('gred.keutamaan', 'desc')
            ->orderBy('kakitangan.nama', 'asc')
            ->get();

        $tarikh = \App\Models\Kakitangan::max('tarikhkemaskini');

        return view('kakitangan.laporan.lantikan', compact('rows', 'tarikh'));
    }
    public function senarai_perjawatan()
    {
        // Perjawatan + gred + jawatan
        $perjawatan = \App\Models\Perjawatan::select(
            'perjawatan.gred as idgred',
            'gred.gred',
            'perjawatan.waran',
            'jawatan.jawatan',
            'perjawatan.bilanganperjawatan as lulus'
        )
            ->leftJoin('gred', 'gred.id', '=', 'perjawatan.gred')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'perjawatan.jawatan')
            ->orderBy('gred.keutamaan', 'desc')
            ->get();

        // Group kakitangan by gred
        $kakitanganByGred = \App\Models\Kakitangan::select(
            'kakitangan.gred',
            'kakitangan.nama',
            'organisasi.kod'
        )
            ->leftJoin('organisasi', 'organisasi.id', '=', 'kakitangan.penempatanoperasi')
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->whereIn('penempatan.kod', ['A', 'B'])
            ->orderBy('organisasi.kod')
            ->orderBy('kakitangan.nama')
            ->get()
            ->groupBy('gred');

        return view('kakitangan.laporan.senarai_perjawatan', compact(
            'perjawatan',
            'kakitanganByGred'
        ));
    }

    public function penempatan()
    {
        $rows = \App\Models\Kakitangan::select(
            'kakitangan.nama',
            'jawatan.kod as kodjawatan',
            'kakitangan.mykad',
            'gred.gred as gredkakitangan',
            'penempatan.kod as kodpenempatan',
            'kakitangan.nowaran as waran',
            'organisasi.kod as operasi',
            'unit.unit'
        )
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->leftJoin('unit', 'unit.id', '=', 'kakitangan.unit')
            ->leftJoin('organisasi', 'organisasi.id', '=', 'kakitangan.penempatanoperasi')
            ->orderBy('gred.keutamaan', 'desc')
            ->get();

        $tarikh = \App\Models\Kakitangan::max('tarikhkemaskini');

        return view('kakitangan.laporan.penempatan', compact('rows', 'tarikh'));
    }
    public function luar_ciast()
    {
        $summary = \App\Models\Kakitangan::select(
            'gred.gred',
            'jawatan.jawatan'
        )
            ->addSelect(\Illuminate\Support\Facades\DB::raw('count(kakitangan.id) as bil'))
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->where('penempatan.kod', 'B')
            ->groupBy('gred.gred', 'jawatan.jawatan', 'gred.keutamaan')
            ->orderBy('gred.keutamaan', 'desc')
            ->get();

        $details = \App\Models\Kakitangan::select(
            'gred.gred',
            'jawatan.jawatan',
            'kakitangan.nama',
            'kakitangan.penempatanbaru'
        )
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->where('penempatan.kod', 'B')
            ->orderBy('gred.keutamaan', 'desc')
            ->get();

        $tarikh = \App\Models\Kakitangan::max('tarikhkemaskini');

        return view('kakitangan.laporan.luar_ciast', compact('summary', 'details', 'tarikh'));
    }
    public function sambilan()
    {
        $summary = \App\Models\Kakitangan::select(
            'gred.gred',
            'jawatan.jawatan'
        )
            ->addSelect(\Illuminate\Support\Facades\DB::raw('count(kakitangan.id) as bil'))
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->where(function ($query) {
                $query->where('penempatan.kod', 'D')
                    ->orWhere('penempatan.kod', 'G');
            })
            ->groupBy('kakitangan.gred', 'gred.gred', 'jawatan.jawatan')
            ->get();

        $sambilan = \App\Models\Kakitangan::select(
            'kakitangan.nama',
            'jawatan.jawatan',
            'gred.gred',
            'unit.unit as unitpenempatan'
        )
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->leftJoin('unit', 'unit.id', '=', 'kakitangan.unit')
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->where('penempatan.kod', 'D')
            ->orderBy('gred.keutamaan', 'desc')
            ->get();

        $contract = \App\Models\Kakitangan::select(
            'kakitangan.nama',
            'penempatan.jenis',
            'gred.gred',
            'jawatan.jawatan',
            'unit.unit'
        )
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->leftJoin('unit', 'unit.id', '=', 'kakitangan.unit')
            ->where('penempatan.kod', 'G')
            ->orderBy('gred.gred', 'desc')
            ->orderBy('kakitangan.nama', 'asc')
            ->get();

        $tarikh = \App\Models\Kakitangan::max('tarikhkemaskini');

        return view('kakitangan.laporan.sambilan', compact('summary', 'sambilan', 'contract', 'tarikh'));
    }
    public function statistik()
    {
        $rows = \App\Models\Penempatan::select(
            'penempatan.kod',
            'penempatan.jenis'
        )
            ->addSelect(\Illuminate\Support\Facades\DB::raw('count(kakitangan.id) as jumlah'))
            ->leftJoin('kakitangan', 'kakitangan.kodpenempatan', '=', 'penempatan.id')
            ->groupBy('penempatan.kod', 'penempatan.jenis')
            ->orderBy('penempatan.kod', 'asc')
            ->get();

        return view('kakitangan.laporan.statistik', compact('rows'));
    }
    public function bersara()
    {
        $rows = \App\Models\Kakitangan::select(
            'kakitangan.nama',
            'kakitangan.mykad',
            'kakitangan.tarikhlantikanpertama',
            'kakitangan.tarikhkeciast',
            'kakitangan.tarikhbertukarkeluar',
            'kakitangan.penempatanbaru'
        )
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->where('penempatan.kod', 'F')
            ->orderBy('kakitangan.tarikhbertukarkeluar', 'desc')
            ->get();

        $tarikh = \App\Models\Kakitangan::max('tarikhkemaskini');

        return view('kakitangan.laporan.bersara', compact('rows', 'tarikh'));
    }
    public function baru()
    {
        $rows = \App\Models\Kakitangan::select(
            'kakitangan.nama',
            'jawatan.kod as jawatan',
            'gred.gred',
            'kakitangan.tarikhkeciast',
            'organisasi.kod as program',
            'unit.unit'
        )
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->leftJoin('organisasi', 'organisasi.id', '=', 'kakitangan.penempatanoperasi')
            ->leftJoin('unit', 'unit.id', '=', 'kakitangan.unit')
            ->whereRaw('DATEDIFF(CURDATE(), kakitangan.tarikhkeciast) < 180')
            ->orderBy('gred.keutamaan', 'desc')
            ->orderBy('kakitangan.nama', 'asc')
            ->get();

        return view('kakitangan.laporan.baru', compact('rows'));
    }
    public function bertukar()
    {
        $rows = \App\Models\Kakitangan::select(
            'kakitangan.nama',
            'kakitangan.tarikhbertukarkeluar',
            'kakitangan.penempatanbaru',
            'jawatan.kod as kodjawatan',
            'gred.gred as nomborgred'
        )
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->where('penempatan.kod', 'E')
            ->orderBy('gred.keutamaan', 'desc')
            ->orderBy('kakitangan.nama', 'asc')
            ->get();

        $tarikh = \App\Models\Kakitangan::max('tarikhkemaskini');

        return view('kakitangan.laporan.bertukar', compact('rows', 'tarikh'));
    }
    public function apc_pingat()
    {
        $rows = \App\Models\Kakitangan::select(
            'kakitangan.id',
            'kakitangan.nama',
            'jawatan.kod as jawatan',
            'gred.gred'
        )
            ->leftJoin('penempatan', 'penempatan.id', '=', 'kakitangan.kodpenempatan')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->where(function ($query) {
                $query->where('penempatan.kod', 'A')
                    ->orWhere('penempatan.kod', 'B')
                    ->orWhere('penempatan.kod', 'C');
            })
            ->with([
                'apc' => function ($query) {
                    $query->orderBy('tahunterima', 'asc');
                },
                'pingat' => function ($query) {
                    $query->orderBy('tarikhterima', 'desc');
                }
            ])
            ->orderBy('gred.keutamaan', 'desc')
            ->get();

        return view('kakitangan.laporan.apc_pingat', compact('rows'));
    }
}
