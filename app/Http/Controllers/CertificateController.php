<?php

namespace App\Http\Controllers;

use App\Models\LMS\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function view($id)
    {
        $certificate = Certificate::findOrFail($id);
        $path = public_path('files/certificates/' . $certificate->certificate_file);

        return response()->file($path);
    }
}
