const CACHE_NAME = "barber-v1";
const OFFLINE_URL = "/offline.html";

const ASSETS = [
    "/",
    "/offline.html",
    "/manifest.webmanifest",
    "/icons/icon-192.png",
    "/icons/icon-512.png",
    "/icons/icon-192-maskable.png",
    "/icons/icon-512-maskable.png",
];

// Install: pre-cache core assets
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS)),
    );
    self.skipWaiting();
});

// Activate: cleanup old caches
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(
                    keys
                        .filter((k) => k !== CACHE_NAME)
                        .map((k) => caches.delete(k)),
                ),
            ),
    );
    self.clients.claim();
});

// Fetch: network-first for HTML, cache-first for assets
self.addEventListener("fetch", (event) => {
    const req = event.request;

    // HTML pages → network first
    if (req.mode === "navigate") {
        event.respondWith(
            fetch(req)
                .then((res) => {
                    const copy = res.clone();
                    caches
                        .open(CACHE_NAME)
                        .then((cache) => cache.put(req, copy));
                    return res;
                })
                .catch(() => caches.match(OFFLINE_URL)),
        );
        return;
    }

    // Static assets → cache first
    event.respondWith(caches.match(req).then((cached) => cached || fetch(req)));
});
