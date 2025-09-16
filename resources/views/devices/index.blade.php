@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Perangkat Saya</h4>
    <a href="{{ route('devices.create') }}" class="btn btn-primary">+ Tambah</a>

    {{-- ✅ Pesan sukses atau gagal --}}
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-4">
        @foreach($devices as $device)
            <div class="card p-3 mb-3">
                <h5>{{ $device->name }}</h5>
                <p>{{ $device->dns_name }} ({{ $device->ip_address }})</p>
                <p>Port: {{ $device->api_port }}</p>
                <a href="{{ route('devices.test', $device->id) }}" class="btn btn-info">Test Koneksi</a>
                <a href="{{ route('devices.edit', $device->id) }}" class="btn btn-warning">Edit</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
