const CACHE_NAME = "minimalist-studio-v1";
const STATIC_CACHE = "minimalist-static-v1";
const DYNAMIC_CACHE = "minimalist-dynamic-v1";

// ── Static assets — Cache-First ──
const STATIC_ASSETS = [
    "/",
    "/login",
    "/register",
    "/offline",
    "/images/minimalist-logo.png",
    "/images/minimalist-logo-2.png",
    "/favicon.ico",
    "/manifest.json",
];

// ── Dynamic routes — Network-First ──
const DYNAMIC_ROUTES = [
    "/home",
    "/activity",
    "/profile",
    "/member",
    "/coach",
    "/payment",
    "/admin/dashboard",
    "/admin/schedules",
    "/admin/coaches",
    "/admin/classes",
    "/admin/membership",
    "/admin/promos",
    "/admin/customers",
    "/admin/keuangan",
    "/coach/dashboard",
];

// ── Install — cache static assets ──
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches
            .open(STATIC_CACHE)
            .then((cache) =>
                Promise.allSettled(
                    STATIC_ASSETS.map((url) =>
                        cache
                            .add(url)
                            .catch((e) =>
                                console.warn("Failed to cache:", url, e),
                            ),
                    ),
                ),
            )
            .then(() => self.skipWaiting()),
    );
});

// ── Activate — clean old caches ──
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(
                    keys
                        .filter(
                            (key) =>
                                key !== STATIC_CACHE && key !== DYNAMIC_CACHE,
                        )
                        .map((key) => caches.delete(key)),
                ),
            )
            .then(() => self.clients.claim()),
    );
});

// ── Fetch — strategy router ──
self.addEventListener("fetch", (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET and cross-origin requests
    if (request.method !== "GET") return;
    if (url.origin !== location.origin) return;

    // Skip Vite HMR
    if (
        url.pathname.startsWith("/@") ||
        url.pathname.startsWith("/node_modules")
    )
        return;

    // Skip webhook
    if (url.pathname === "/payment/webhook") return;

    const isDynamic = DYNAMIC_ROUTES.some((route) =>
        url.pathname.startsWith(route),
    );

    if (isDynamic) {
        // Network-First for dynamic pages
        event.respondWith(networkFirst(request));
    } else {
        // Cache-First for static assets
        event.respondWith(cacheFirst(request));
    }
});

// ── Cache-First strategy ──
async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(STATIC_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        return (
            caches.match("/offline") || new Response("Offline", { status: 503 })
        );
    }
}

// ── Network-First strategy ──
async function networkFirst(request) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        const cached = await caches.match(request);
        if (cached) return cached;
        return (
            caches.match("/offline") || new Response("Offline", { status: 503 })
        );
    }
}
