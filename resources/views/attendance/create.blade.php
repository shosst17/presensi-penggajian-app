@extends('layouts.admin')

@section('title', 'Form Absensi')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<style>
    #map { height: 350px; width: 100%; border: 3px solid #ccc; border-radius: 10px; }
    #my_camera { width: 100% !important; height: auto !important; min-height: 240px; border: 3px solid #ccc; border-radius: 10px; margin: 0 auto; }
</style>

<div class="row">
    <div class="col-12 mb-3">
        <div class="alert alert-secondary text-center">
            <h5 class="mb-0">
                Status: 
                @if(!$attendance)
                    <span class="badge bg-primary">BELUM ABSEN</span>
                @elseif($attendance->check_in_time && !$attendance->check_out_time)
                    <span class="badge bg-warning text-dark">SUDAH MASUK ({{ $attendance->check_in_time }})</span>
                @else
                    <span class="badge bg-success">SELESAI KERJA</span>
                @endif
            </h5>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-outline card-primary h-100">
            <div class="card-header"><h3 class="card-title">1. Selfie</h3></div>
            <div class="card-body text-center">
                @if(isset($attendance) && $attendance->check_out_time)
                    <div class="alert alert-success">Terima kasih atas kerja keras Anda hari ini!</div>
                @else
                    <div id="my_camera" class="mb-3"></div>
                    <div id="results" style="display:none;" class="mb-3"></div>
                    <button type="button" class="btn btn-primary" onClick="take_snapshot()" id="btn-cam">Jepret Foto</button>
                    <button type="button" class="btn btn-warning" onClick="reset_camera()" id="btn-reset" style="display:none;">Ulangi</button>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-outline card-success h-100">
            <div class="card-header"><h3 class="card-title">2. Lokasi</h3></div>
            <div class="card-body">
                <div id="map"></div>
                <p id="status-lokasi" class="mt-2 text-center fw-bold text-muted">Mencari koordinat...</p>
            </div>
        </div>
    </div>
</div>

@if(!isset($attendance) || !$attendance->check_out_time)
<div class="row mt-4 mb-5">
    <div class="col-12">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="long">
            <input type="hidden" name="image" id="image">
            
            <button type="submit" class="btn w-100 py-3 fw-bold shadow {{ $attendance ? 'btn-danger' : 'btn-success' }}" id="btn-submit" disabled>
                @if(!$attendance)
                    <i class="bi bi-box-arrow-in-right"></i> ABSEN MASUK
                @else
                    <i class="bi bi-box-arrow-left"></i> ABSEN PULANG
                @endif
            </button>
        </form>
    </div>
</div>
@endif

<script>
    @if(!isset($attendance) || !$attendance->check_out_time)
    
    document.addEventListener("DOMContentLoaded", function() {
        initCamera();
        initMap();
    });

    function initCamera() {
        Webcam.set({ width: 320, height: 240, image_format: 'jpeg', jpeg_quality: 90 });
        Webcam.attach('#my_camera');
    }

    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'" class="img-fluid rounded"/>';
            document.getElementById('image').value = data_uri;
            document.getElementById('my_camera').style.display = 'none';
            document.getElementById('results').style.display = 'block';
            document.getElementById('btn-cam').style.display = 'none';
            document.getElementById('btn-reset').style.display = 'inline-block';
            cekTombol();
        });
    }

    function reset_camera() {
        document.getElementById('image').value = '';
        document.getElementById('my_camera').style.display = 'block';
        document.getElementById('results').style.display = 'none';
        document.getElementById('btn-cam').style.display = 'inline-block';
        document.getElementById('btn-reset').style.display = 'none';
        cekTombol();
    }

    function initMap() {
        var latK = {{ $office->latitude }};
        var lngK = {{ $office->longitude }};
        var radK = {{ $office->radius_meters }};

        var map = L.map('map').setView([latK, lngK], 15);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        L.circle([latK, lngK], { color: 'red', fillColor: '#f03', fillOpacity: 0.2, radius: radK }).addTo(map).bindPopup("Kantor").openPopup();
        var marker = L.marker([0,0]).addTo(map);

        if(navigator.geolocation) {
            navigator.geolocation.watchPosition((pos) => {
                var lat = pos.coords.latitude;
                var lng = pos.coords.longitude;
                document.getElementById('lat').value = lat;
                document.getElementById('long').value = lng;
                
                marker.setLatLng([lat, lng]).bindPopup("Saya").openPopup();
                map.setView([lat, lng], 17);
                
                var dist = map.distance([lat, lng], [latK, lngK]);
                var stat = document.getElementById('status-lokasi');
                
                if(dist <= radK) {
                    stat.innerHTML = '<span class="text-success">✅ Dalam Jangkauan ('+Math.round(dist)+'m)</span>';
                } else {
                    stat.innerHTML = '<span class="text-danger">❌ Terlalu Jauh ('+Math.round(dist)+'m)</span>';
                }
                cekTombol();
            }, (err) => { alert("Wajib Aktifkan GPS!"); }, { enableHighAccuracy: true });
        }
    }

    function cekTombol() {
        var lat = document.getElementById('lat').value;
        var img = document.getElementById('image').value;
        if(lat && img) document.getElementById('btn-submit').disabled = false;
        else document.getElementById('btn-submit').disabled = true;
    }
    @endif
</script>
@endsection