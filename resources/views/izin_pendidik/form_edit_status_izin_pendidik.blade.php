<form action="/update_status_izin_pendidik_{{ $izin_pendidik->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="status_izin">Status Izin:</label>
        <select class="form-control" id="status_izin" name="status_izin">
            @foreach ($status_izin as $status)
                <option value="{{ $status->id }}" {{ $izin_pendidik->id_status_izin == $status->id ? 'selected' : '' }}>
                    {{ $status->status }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
