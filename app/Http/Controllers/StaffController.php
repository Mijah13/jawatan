<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Kakitangan;

class StaffController extends Controller
{
    public function index() {
        $data = Kakitangan::all();
        return view('staff.index', compact('data'));
    }
}
