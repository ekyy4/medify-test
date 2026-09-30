<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;

class KategoriItemsController extends Controller
{
    public function index()
    {
        $data['items'] = KategoriItem::orderBy('id')->get();
        return view('kategori_items.index.index', $data);
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = KategoriItem::query();

        if (!empty($kode))
            $data_search = $data_search->where('kode', $kode);
        if (!empty($nama))
            $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->withCount('masterItems')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = KategoriItem::findOrFail($id);
        }

        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = KategoriItem::with('masterItems')->where('kode', $kode)->firstOrFail();
        return view('kategori_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if ($method == 'new') {
            $item = new KategoriItem;
        } else {
            $item = KategoriItem::findOrFail($id);
        }

        $item->nama = $request->nama;
        $item->kode = $request->kode;
        $item->save();


        return redirect('kategori-items');
    }

    public function delete($id)
    {
        $data_item = KategoriItem::findOrFail($id);

        $affectCount = $data_item->masterItems()->count();
        $nama = $data_item->nama;

        DB::transaction(function () use ($data_item) {
            $data_item->masterItems()->detach();
            $data_item->delete();
        });

        $message = $affectCount > 0 ? "Kategori '{$nama}' berhasil dihapus." : "Kategori '{$nama}' berhasil dihapus";

        return redirect('kategori-items')->with('success', $message);
    }

    public function downloadPdf($kode)
    {
        $data['data'] = KategoriItem::with('masterItems')
            ->where('kode', $kode)
            ->firstOrFail();

        $data['printed_at'] = now()
            ->timezone('Asia/Jakarta')
            ->format('d/m/Y H:i:s') . ' WIB';

        $pdf = Pdf::loadView('kategori_items.single.pdf', $data)
            ->setPaper('a4', 'portrait');

        $nama_file = 'kategori-item-' .
            preg_replace('/[^A-Za-z0-9_-]/', '_', $data['data']->kode) .
            '.pdf';

        return $pdf->download($nama_file);
    }
}
