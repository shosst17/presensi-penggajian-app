self.addEventListener("install", function (event) {
    console.log("[PWA] Service Worker installing.");
    // Di sini kita bisa cache file aset jika mau offline mode
});

self.addEventListener("fetch", function (event) {
    // Standard fetch handler
    // Aplikasi kita butuh online terus (karena GPS/Server), jadi kita tidak cache halaman utama.
});
