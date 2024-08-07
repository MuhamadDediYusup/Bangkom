<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MD_DiklatStrukturalController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Master Diklat Struktural',
            'diklat_struktural' => getData('master_dikstruk', 999),
        ];

        return view('md_diklatstruktural.index', $data);
    }
}
