// ==========================================
// TuhomesTay Service Worker
// Untuk PWA: caching + offline support
// ==========================================

const CACHE_VERSION = 'tuhomestay-v1';
const CACHE_NAME = `${CACHE_VERSION}-shell`;
const OFFLINE_URL = '/offline.html';

// Asset statis yang di-cache saat install
const PRECACHE_URLS = [
    '/offline.html',
    '/manifest.json',
    '/image/logo.png',
    '/image/pwa-icon-192.png'
];

// ========== INSTALL ==========
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('SW: Pre-caching offline shell');
                return cache.addAll(PRECACHE_URLS);
            })
            .then(() => self.skipWaiting())
    );
});

// ========== ACTIVATE ==========
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            );
        }).then(() => self.clients.claim())
    );
});

// ========== FETCH ==========
self.addEventListener('fetch', (event) => {
    const req = event.request;

    // Skip non-GET requests
    if (req.method !== 'GET') return;

    // Skip admin dynamic pages (selalu fresh dari server)
    const url = new URL(req.url);
    if (url.pathname.startsWith('/admin')) {
        event.respondWith(
            fetch(req).catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    // Untuk asset statis (gambar, css, js) → cache-first
    if (
        url.pathname.match(/\.(png|jpg|jpeg|gif|svg|webp|ico|css|js|woff|woff2|ttf)$/i) ||
        url.hostname.includes('fonts.googleapis.com') ||
        url.hostname.includes('fonts.gstatic.com') ||
        url.hostname.includes('cdnjs.cloudflare.com')
    ) {
        event.respondWith(
            caches.match(req).then((cached) => {
                if (cached) return cached;
                return fetch(req).then((res) => {
                    if (!res || res.status !== 200 || res.type === 'opaque') return res;
                    const resClone = res.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(req, resClone));
                    return res;
                }).catch(() => caches.match(OFFLINE_URL));
            })
        );
        return;
    }

    // Default: network-first, fallback ke cache, fallback ke offline
    event.respondWith(
        fetch(req)
            .then((res) => {
                if (res && res.status === 200 && res.type === 'basic') {
                    const resClone = res.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(req, resClone));
                }
                return res;
            })
            .catch(() => {
                return caches.match(req).then((cached) => {
                    return cached || caches.match(OFFLINE_URL);
                });
            })
    );
});

// ========== PUSH NOTIFICATION (opsional, aktifkan saat hosting) ==========
self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'TuhomesTay';
    const options = {
        body: data.body || 'Ada notifikasi baru',
        icon: '/image/pwa-icon-192.png',
        badge: '/image/pwa-icon-192.png',
        vibrate: [100, 50, 100],
        data: { url: data.url || '/admin' }
    };
    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data.url || '/admin';
    event.waitUntil(
        clients.matchAll({ type: 'window' }).then((clientList) => {
            for (const client of clientList) {
                if (client.url === url && 'focus' in client) return client.focus();
            }
            if (clients.openWindow) return clients.openWindow(url);
        })
    );
});