<?php

namespace App\Http\Controllers;


use App\Models\transaksi;
use Illuminate\Http\Request;
use App\Models\transaksiDetail;
use Illuminate\Support\Facades\Gate;


class LaporanController extends Controller
{

    public function index(Request $request){
        if(!Gate::allows('read role')){
            return view('error.401');
        }
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = transaksi::where('created_at', 'LIKE', "%$tanggal%")->sum('total_harga');
            $total_item = transaksi::where('created_at', 'LIKE', "%$tanggal%")->sum('total_item');
            $total_transaksi = TransaksiDetail::whereHas('transaksi', function ($query) use ($tanggal) {
                $query->whereDate('created_at', $tanggal);
            })->count();

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_uang($total_penjualan);
            $row['total_item'] = ($total_item);
            $row['total_transaksi'] = ($total_transaksi);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => '',
            'total_item' => '',
            'total_transaksi' => '',
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        // Render view Blade dan tampilkan datanya
        return view('laporan.pdf', compact('awal', 'akhir', 'data'));

        // Jika Anda masih ingin melanjutkan untuk menghasilkan dan men-download PDF, gunakan kode di bawah ini:
        // $pdf = PDF::loadView('laporan.pdf', compact('awal', 'akhir', 'data'));
        // $pdf->setPaper('a4', 'portrait');
        // return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }

}
