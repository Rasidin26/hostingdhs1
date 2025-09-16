<form action="{{ route('voucher_templates.store') }}" method="POST">
    @csrf
    <label>Nama Template</label>
    <input type="text" name="name" required>

    <label>Kode HTML Template</label>
    <textarea name="template" rows="10" required></textarea>

    <button type="submit">Simpan Template</button>
</form>
