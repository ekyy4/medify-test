<form method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="">Kode</label>
        <input type="text" name="kode" class="form-control" required value="{{ old('kode', $item->kode ?? '') }}">
        <small class="text-muted">Kode harus unit, tidak boleh sama dengan kategori lain</small>
    </div>
    <div class="form-group">
        <label for="">Nama Kategori</label>
        <input type="text" name="nama" class="form-control" required value="{{ old('nama', $item->nama ?? '') }}">

        @error('nama')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>
