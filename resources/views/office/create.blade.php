@extends('layouts.admin')
@section('title', 'Tambah Kantor')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<form action="{{ route('office.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-5">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Info & Aturan Kantor</h3></div>
                <div class="card-body">
                    
                    <div class="form-group mb-3">
                        <label>Nama Kantor</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Pusat Jakarta" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="text-success"><i class="bi bi-clock"></i> Jam Masuk</label>
                                <input type="time" name="start_time" class="form-control" value="08:00" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="text-danger"><i class="bi bi-clock-history"></i> Jam Pulang</label>
                                <input type="time" name="end_time" class="form-control" value="17:00" required>
                            </div>
                        </div>
                    </div>

                    <hr>
                    
                    <h6 class="text-primary fw-bold">Aturan Toleransi & Lembur</h6>
                    
                    <div class="row mb-2">
                        <div class="col-6">
                            <label>Toleransi Telat</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="entry_grace_minutes" class="form-control" value="10">
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Toleransi Pulang</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="exit_grace_minutes" class="form-control" value="30">
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label>Min. Lembur</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="min_overtime_minutes" class="form-control" value="60">
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Max. Lembur</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="max_overtime_minutes" class="form-control" value="120">
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group mb-3">
                        <label>Radius Absen (Meter)</label>
                        <input type="number" name="radius_meters" id="radius" class="form-control" value="50">
                    </div>
                    
                    <div class="form-group mt-2">
                        <label>Koordinat (Klik Peta)</label>
                        <div class="row">
                            <div class="col-6"><input type="text" name="latitude" id="lat" class="form-control form-control-sm" readonly required placeholder="Lat"></div>
                            <div class="col-6"><input type="text" name="longitude" id="lng" class="form-control form-control-sm" readonly required placeholder="Long"></div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mt-3"><i class="bi bi-save"></i> Simpan Kantor</button>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card card-outline card-success">
                <div class="card-header"><h3 class="card-title">Pilih Lokasi di Peta</h3></div>
                <div class="card-body p-0">
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var map = L.map('map').setView([-6.2088, 106.8456], 13); // Default Jakarta
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    var marker = L.marker([-6.2088, 106.8456], {draggable: true}).addTo(map);
    var circle = L.circle([-6.2088, 106.8456], {color: 'red', fillColor: '#f03', fillOpacity: 0.2, radius: 50}).addTo(map);

    function updateInput(lat, lng) {
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
        circle.setLatLng([lat, lng]);
    }

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateInput(e.latlng.lat, e.latlng.lng);
    });

    marker.on('dragend', function(e) {
        var pos = marker.getLatLng();
        updateInput(pos.lat, pos.lng);
    });

    document.getElementById('radius').addEventListener('input', function(e) {
        circle.setRadius(this.value);
    });
</script>
@endsection