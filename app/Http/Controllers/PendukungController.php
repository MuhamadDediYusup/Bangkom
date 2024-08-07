<?php

namespace App\Http\Controllers;

use App\Models\Petunjuk;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PendukungController extends Controller
{
    public function about()
    {
        $data = [
            'title' => 'About',
            'about' => About::first(),
        ];
        return view('pendukung.about', $data);
    }

    public function editAbout()
    {
        $data = [
            'title' => 'Edit About',
            'about' => About::first(),
        ];
        return view('pendukung.edit_about', $data);
    }

    public function updateAbout(Request $request)
    {
        $about = About::first();
        $about->update($request->all());
        return redirect()->route('pendukung.about')->with('success', 'About abangkomandan berhasil diubah');
    }

    public function petunjuk()
    {
        $data = [
            'title' => 'Petunjuk',
            'petunjuk' => Petunjuk::first(),
        ];
        return view('pendukung.petunjuk', $data);
    }

    public function editPetunjuk(Request $request)
    {
        $data = [
            'title' => 'Edit File Petunjuk Penggunaan',
            'petunjuk' => Petunjuk::find($request->id_petunjuk),
        ];
        return view('pendukung.edit_pendukung', $data);
    }

    public function updatePetunjuk(Request $request)
    {
        $petunjuk = Petunjuk::first();

        if (File::exists(public_path('petunjuk_file/' . $petunjuk->file_petunjuk))) {
            File::delete(public_path('petunjuk_file/' . $petunjuk->file_petunjuk));
        }

        $petunjuk->file_petunjuk = $request->file_petunjuk->getClientOriginalName();
        $petunjuk->save();

        $request->file_petunjuk->move(public_path('/petunjuk_file'), $request->file_petunjuk->getClientOriginalName());

        return redirect()->route('pendukung.petunjuk')->with('success', 'File Petunjuk Penggunaan berhasil diubah');
    }
}
