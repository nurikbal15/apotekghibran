<?php

namespace App\Http\Controllers;

use App\Models\rak;
use App\Models\obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        if(!Gate::allows('read role')){
            return view('error.401');
        }
        $obats = obat::all();
        return view('obat.index', compact('obats'));

        // Debugging data id
        // foreach ($obats as $obat) {
        //     dd($obat->id, gettype($obat->id));
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rakTersedia = rak::all();
        return view('obat.create', compact('rakTersedia'));
        // return view('obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // Validasi data yang dikirimkan
    $validator = Validator::make($request->all(), [
        'id' => 'required|string|size:6|unique:obats,id',
        'nama_obat' => 'required|string|max:255',
        'nama_produsen' => 'required|string|max:255',
        'stok' => 'required|integer',
        'tgl_kadaluarsa' => 'required|date',
        'dosis' => 'required|string|max:255',
        'harga_beli' => 'required|integer',
        'harga_jual' => 'required|integer',
        'rak_id' => 'required|unique:obats,rak_id',
    ]);

    if ($validator->fails()) {
        // Redirect back to the form with validation errors and old input
        return redirect('obat/create')
            ->withErrors($validator)
            ->withInput();
    }

    // Proses hanya akan dilanjutkan jika validasi berhasil
    else
    {
        $tgl_kadaluarsa = date('Y-m-d', strtotime($request->input('tgl_kadaluarsa')));

        $validatedData = $validator->validated();

        $obat = new Obat;
        $obat->id = $validatedData['id'];
        $obat->nama_obat = $validatedData['nama_obat'];
        $obat->nama_produsen = $validatedData['nama_produsen'];
        $obat->stok = $validatedData['stok'];
        $obat->tgl_kadaluarsa = $tgl_kadaluarsa;
        $obat->dosis = $validatedData['dosis'];
        $obat->harga_beli = $validatedData['harga_beli'];
        $obat->harga_jual = $validatedData['harga_jual'];
        $obat->rak_id = $request->input('rak_id');
        $obat->user_id = auth()->user()->id; // Mengambil ID admin yang terautentikasi saat ini

        $obat->save();

        return redirect()->route('obat.index')->with('success', 'Data berhasil disimpan.');
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(obat $obat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $obat = obat::find($id);
        $rakTersedia = rak::all();
        return view('obat.edit', compact('obat', 'rakTersedia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $obat = obat::find($id);
        if(!$obat){
            return response()->json(['massage'=>'Data tidak ditemukan'], 404);
        }

        $obat->update($request->all());
        return redirect()->route('obat.index')->with('success', 'Data obat berhasil diupdate.');//pop upnya belum tampil
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $obat = obat::findOrFail($id);
        if (!$obat) {
            return redirect()->route('obat.index')->with('error', 'Data tidak ditemukan.');
        }
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil dihapus.');
    }

}
