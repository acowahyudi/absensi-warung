const CACHE_NAME = 'ganjs-absensi-v1';
const STATIC_ASSETS = [
    '/offline.html',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

// Cache-first untuk aset statis
const CACHE_FIRST_PATTERNS = [
    /\/build\//,           // Vite assets
    /\/icons\//,           // PWA icons
    /fonts\.googleapis\.com/,
    /fonts\.gstatic\.com/,
];

// Network-first untuk halaman dinamis
const NETWORK_FIRST_PATTERNS = [
    /\/karyawan\//,
    /\/admin\//,
    /\/livewire\//,
];

// Install: cache aset statis
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS).catch(() => {
                // Jika offline.html belum ada, lanjutkan saja
            });
        })
    );
    self.skipWaiting();
});

// Activate: hapus cache lama
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        })
    );
    self.clients.claim();
});

// Fetch: strategi hybrid
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Abaikan non-GET dan request ke API Livewire internal
    if (request.method !== 'GET') return;
    if (url.pathname.startsWith('/livewire/update')) return;

    // Cache-first untuk aset statis
    if (CACHE_FIRST_PATTERNS.some((pattern) => pattern.test(url.href))) {
        event.respondWith(
            caches.match(request).then((cached) => {
                return cached || fetch(request).then((response) => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
                    }
                    return response;
                });
            })
        );
        return;
    }

    // Network-first untuk halaman dinamis — fallback ke offline page
    if (NETWORK_FIRST_PATTERNS.some((pattern) => pattern.test(url.pathname))) {
        event.respondWith(
            fetch(request).catch(() => {
                return caches.match('/offline.html');
            })
        );
        return;
    }
});
