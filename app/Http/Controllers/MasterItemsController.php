<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $data_search = MasterItem::query();

        if ($request->filled('kode')) {
            $data_search->where('kode', $request->kode);
        }

        if ($request->filled('nama')) {
            $data_search->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->filled('hargamin')) {
            $data_search->where('harga_beli', '>=', $request->hargamin);
        }

        if ($request->filled('hargamax')) {
            $data_search->where('harga_beli', '<=', $request->hargamax);
        }

        $data_search = $data_search
            ->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')
            ->orderBy('id')
            ->get();

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
            $item = MasterItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategori_items'] = KategoriItem::orderBy('nama')->get();
        $data['kategori_terpilih'] = $method == 'new'
            ? []
            : $item->kategoriItems->pluck('id')->toArray();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kategori_items' => 'nullable|array',
            'kategori_items.*' => 'exists:kategori_items,id',
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $foto_lama = $data_item->foto;

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;


        if ($request->hasFile('foto')) {
            $data_item->foto = $request->file('foto')->store('master-items', 'public');
        }

        $data_item->save();
        $data_item->kategoriItems()->sync($request->kategori_items ?? []);

        if ($request->hasFile('foto') && $foto_lama) {
            Storage::disk('public')->delete($foto_lama);
        }

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }

    public function exportExcel()
    {
        $items = MasterItem::with('kategoriItems')
            ->orderBy('id')
            ->get();

        $rows = collect([
            [
                'No',
                'Nama Kategori',
                'Nama Items',
                'Nama Suplier',
                'Harga',
                'Laba (%)',
                'Harga Jual'
            ]
        ]);

        foreach ($items as $index => $item) {
            $harga_jual = round(
                $item->harga_beli + ($item->harga_beli * $item->laba / 100)
            );

            $rows->push([
                $index + 1,
                $item->kategoriItems->pluck('nama')->implode(', ') ?: '-',
                $item->nama,
                $item->supplier,
                $item->harga_beli,
                $item->laba,
                $harga_jual
            ]);
        }

        return $rows->downloadExcel('master-items.xlsx');
    }
}
