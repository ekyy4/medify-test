<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    <div class="form-group">
        <label for="">Foto</label>
        <input type="file" class="form-control" name="foto" value="{{ $item->foto ?? '' }}" accept="image/jpeg,image/png,image/webp">

        @error('foto')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        @if (!empty($item->foto))
            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto {{ $item->nama }}" class="img-thumbnail mt-2" style="max-width: 150px;">
        @endif
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <optio @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <optio @if($selected == 'Umum') selected @endif>Umum</option>
            <optio @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kategori Item</label>

        @forelse($kategori_items as $kategori)
            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="kategori_items[]"
                    id="kategori-{{ $kategori->id }}"
                    value="{{ $kategori->id }}"
                    @if(in_array($kategori->id, old('kategori_items', $kategori_terpilih))) checked @endif
                >
                <label class="form-check-label" for="kategori-{{ $kategori->id }}">
                    {{ $kategori->nama }}
                </label>
            </div>
        @empty
            <div class="text-muted">Belum ada kategori item.</div>
        @endforelse
    </div>
    <button class="btn btn-primary mt-3">Submit</button>

</form>
