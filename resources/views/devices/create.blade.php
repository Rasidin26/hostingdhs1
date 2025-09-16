@extends('layouts.app')

@section('content')
<div class="container">
<h5 class="mb-4 fw-bold">
    <i class="fa-solid fa-plus text-success me-2"></i> Tambah Perangkat
</h5>
    <form action="{{ route('devices.store') }}" method="POST" class="p-4 bg-white shadow-sm rounded">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold text-dark small">Nama Perangkat</label>
            <input type="text" name="name" 
                   class="form-control" 
                   placeholder="Masukkan nama perangkat" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold text-dark small">DNS Name (Opsional)</label>
            <input type="text" name="dns_name" 
                   class="form-control" 
                   placeholder="Masukkan DNS Name">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold text-dark small">IP Address</label>
<input type="text" name="ip_address" 
       class="form-control" 
       placeholder="Contoh: 192.168.1.1" 
       pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" 
       required>

        </div>
        <div class="mb-3">
            <label class="form-label fw-bold text-dark small">Port API</label>
            <input type="number" name="api_port" 
                   class="form-control" 
                   placeholder="8278" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold text-dark small">Username</label>
            <input type="text" name="username" 
                   class="form-control" 
                   placeholder="Masukan Username" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold text-dark small">Password</label>
            <input type="password" name="password" 
                   class="form-control" 
                   placeholder="Masukkan password" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
