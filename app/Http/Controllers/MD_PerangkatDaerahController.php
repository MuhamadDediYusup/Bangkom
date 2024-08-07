<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MD_PerangkatDaerahController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Master Perangkat Daerah',
            'perangkat_daerah' => getData('master_perangkat_daerah', 1),
        ];

        return view('md_perangkatdaerah.index', $data);
    }
}
