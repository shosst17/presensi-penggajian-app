@extends('layouts.admin')
@section('title', 'Edit Kantor')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<form action="{{ route('office.update', $office->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-5">
            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">Edit Aturan Kantor</h3></div>
                <div class="card-body">
                    
                    <div class="form-group mb-3">
                        <label>Nama Kantor</label>
                        <input type="text" name="name" class="form-control" value="{{ $office->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control" rows="2">{{ $office->address }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <label class="text-success"><i class="bi bi-clock"></i> Masuk</label>
                            <input type="time" name="start_time" class="form-control" value="{{ $office->start_time }}" required>
                        </div>
                        <div class="col-6">
                            <label class="text-danger"><i class="bi bi-clock-history"></i> Pulang</label>
                            <input type="time" name="end_time" class="form-control" value="{{ $office->end_time }}" required>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary fw-bold">Aturan Toleransi & Lembur</h6>

                    <div class="row mb-2">
                        <div class="col-6">
                            <label>Tol. Telat</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="entry_grace_minutes" class="form-control" value="{{ $office->entry_grace_minutes }}" required>
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Tol. Pulang</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="exit_grace_minutes" class="form-control" value="{{ $office->exit_grace_minutes }}" required>
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label>Min. Lembur</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="min_overtime_minutes" class="form-control" value="{{ $office->min_overtime_minutes }}" required>
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Max. Lembur</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="max_overtime_minutes" class="form-control" value="{{ $office->max_overtime_minutes }}" required>
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group mb-3">
                        <label>Radius (Meter)</label>
                        <input type="number" name="radius_meters" id="radius" class="form-control" value="{{ $office->radius_meters }}">
                    </div>

                    <div class="form-group mt-2">
                        <label>Koordinat</label>
                        <div class="row">
                            <div class="col-6"><input type="text" name="latitude" id="lat" class="form-control form-control-sm" value="{{ $office->latitude }}" readonly></div>
                            <div class="col-6"><input type="text" name="longitude" id="lng" class="form-control form-control-sm" value="{{ $office->longitude }}" readonly></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 mt-3"><i class="bi bi-save"></i> Update Data</button>
                    <a href="{{ route('office.index') }}" class="btn btn-secondary w-100 mt-2">Batal</a>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card card-outline card-warning">
                <div class="card-header"><h3 class="card-title">Lokasi Peta</h3></div>
                <div class="card-body p-0">
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // Logic Peta (Sama seperti sebelumnya)
    var curLat = {{ $office->latitude }};
    var curLng = {{ $office->longitude }};
    var curRad = {{ $office->radius_meters }};
    var map = L.map('map').setView([curLat, curLng], 16);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    var marker = L.marker([curLat, curLng], {draggable: true}).addTo(map);
    var circle = L.circle([curLat, curLng], {color: 'red', fillColor: '#f03', fillOpacity: 0.2, radius: curRad}).addTo(map);

    function updateInput(lat, lng) {
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
        circle.setLatLng([lat, lng]);
    }
    marker.on('dragend', function(e) { updateInput(marker.getLatLng().lat, marker.getLatLng().lng); });
    map.on('click', function(e) { 
        marker.setLatLng(e.latlng); 
        updateInput(e.latlng.lat, e.latlng.lng); 
    });
    document.getElementById('radius').addEventListener('input', function(e) { circle.setRadius(this.value); });
</script>
@endsection