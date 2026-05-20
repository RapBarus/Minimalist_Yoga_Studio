// ============================================================
// sw-cache-first.js — Pure Cache-First Strategy
// For research: Tugas Akhir PWA Caching Comparison
// TTL: 5 minutes for dynamic pages
// ============================================================

const CACHE_NAME = "minimalist-cache-first-v1";
const STATIC_CACHE = "minimalist-cf-static-v1";
const DYNAMIC_CACHE = "minimalist-cf-dynamic-v1";

const CACHE_TTL_MS = 5 * 60 * 1000; // 5 minutes

// ── Static assets ──
const STATIC_ASSETS = [
    "/offline",
    "/images/minimalist-logo.png",
    "/images/minimalist-logo-2.png",
    "/favicon.ico",
    "/manifest.json",
];

// ── Routes subject to Cache-First with TTL ──
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
                                console.warn("[CF] Failed to cache:", url, e),
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

// ── Fetch — Cache-First for everything ──
self.addEventListener("fetch", (event) => {
    const { request } = event;
    const url = new URL(request.url);

    if (request.method !== "GET") return;
    if (url.origin !== location.origin) return;
    if (
        url.pathname.startsWith("/@") ||
        url.pathname.startsWith("/node_modules")
    )
        return;
    if (url.pathname === "/payment/webhook") return;
    if (["/login", "/register", "/"].includes(url.pathname)) return;

    const isDynamic = DYNAMIC_ROUTES.some((route) =>
        url.pathname.startsWith(route),
    );

    if (isDynamic) {
        event.respondWith(cacheFirstWithTTL(request));
    } else {
        event.respondWith(cacheFirst(request));
    }
});

// ── Cache-First with TTL (for dynamic routes) ──
async function cacheFirstWithTTL(request) {
    const cache = await caches.open(DYNAMIC_CACHE);
    const cachedResponse = await cache.match(request);

    if (cachedResponse) {
        const cachedAt = cachedResponse.headers.get("sw-cached-at");
        const age = cachedAt ? Date.now() - parseInt(cachedAt, 10) : Infinity;

        if (age < CACHE_TTL_MS) {
            // Cache hit within TTL — return immediately
            console.log("[CF] Cache hit (fresh):", request.url);
            return cachedResponse;
        } else {
            // Cache expired — try network, fall back to stale cache
            console.log("[CF] Cache expired, fetching network:", request.url);
        }
    } else {
        console.log("[CF] Cache miss, fetching network:", request.url);
    }

    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok && networkResponse.status === 200) {
            const clone = networkResponse.clone();
            const text = await clone.text();
            if (text.length > 500) {
                const headers = new Headers(networkResponse.headers);
                headers.set("sw-cached-at", Date.now().toString());
                const responseToCache = new Response(text, {
                    status: networkResponse.status,
                    statusText: networkResponse.statusText,
                    headers,
                });
                cache.put(request, responseToCache);
            }
        }
        return networkResponse;
    } catch {
        // Network failed — serve stale cache if available
        if (cachedResponse) {
            console.warn(
                "[CF] Network failed, serving stale cache:",
                request.url,
            );
            return cachedResponse;
        }
        const offlinePage = await caches.match("/offline");
        if (offlinePage) return offlinePage;
        return new Response("Offline", { status: 503 });
    }
}

// ── Cache-First without TTL (for static assets) ──
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
        const offlinePage = await caches.match("/offline");
        if (offlinePage) return offlinePage;
        return new Response("Offline", { status: 503 });
    }
}
