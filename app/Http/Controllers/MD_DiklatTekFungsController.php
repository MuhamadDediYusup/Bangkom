<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MD_DiklatTekFungsController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Master Diklat Teknis Fungsional',
            'diktekfungs' => getData('master_diktekfungs', 99),
        ];

        return view('md_diktekfungs.index', $data);
    }
}
