@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Detail Perangkat</h4>
    <div class="card p-3">
        <h5>{{ $device->name }}</h5>
        <p>DNS: {{ $device->dns_name }}</p>
        <p>IP: {{ $device->ip_address }}</p>
        <p>Port: {{ $device->api_port }}</p>
        <p>Username: {{ $device->username }}</p>
    </div>
</div>
@endsection
