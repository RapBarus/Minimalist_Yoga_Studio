// ============================================================
// sw-network-first.js — Pure Network-First Strategy
// For research: Tugas Akhir PWA Caching Comparison
// Network timeout: 3 seconds before falling back to cache
// ============================================================

const CACHE_NAME = "minimalist-network-first-v2";
const STATIC_CACHE = "minimalist-nf-static-v2";
const DYNAMIC_CACHE = "minimalist-nf-dynamic-v2";

const NETWORK_TIMEOUT_MS = 8000; // 3 seconds

// ── Static assets ──
const STATIC_ASSETS = [
    "/offline",
    "/images/minimalist-logo.png",
    "/images/minimalist-logo-2.png",
    "/favicon.ico",
    "/manifest.json",
];

// ── Routes subject to Network-First ──
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
                                console.warn("[NF] Failed to cache:", url, e),
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

// ── Fetch — Network-First for everything ──
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
    if (/\/\d+/.test(url.pathname)) return; // Skip dynamic ID routes
    if (["/login", "/register", "/"].includes(url.pathname)) return;

    if (url.pathname.startsWith("/payment")) return;
    if (url.pathname.startsWith("/membership/payment")) return;
    if (url.pathname === "/logout") return;

    const isDynamic = DYNAMIC_ROUTES.some((route) =>
        url.pathname.startsWith(route),
    );

    if (isDynamic) {
        event.respondWith(networkFirstWithTimeout(request));
    } else {
        event.respondWith(networkFirstWithTimeout(request));
    }
});

// ── Network-First with timeout ──
async function networkFirstWithTimeout(request) {
    const cache = await caches.open(DYNAMIC_CACHE);

    // Race: network vs timeout
    const networkPromise = fetchAndCache(request, cache);
    const timeoutPromise = new Promise((_, reject) =>
        setTimeout(
            () => reject(new Error("Network timeout")),
            NETWORK_TIMEOUT_MS,
        ),
    );

    try {
        const response = await Promise.race([networkPromise, timeoutPromise]);
        console.log("[NF] Network success:", request.url);
        return response;
    } catch (err) {
        console.warn(
            "[NF] Network failed/timeout, falling back to cache:",
            request.url,
            err.message,
        );

        // Fallback: check dynamic cache first, then static cache
        const cached =
            (await cache.match(request)) || (await caches.match(request));
        if (cached) return cached;

        const offlinePage = await caches.match("/offline");
        if (offlinePage) return offlinePage;
        return new Response("Offline", { status: 503 });
    }
}

// ── Fetch from network and store in cache ──
async function fetchAndCache(request, cache) {
    const response = await fetch(request);

    // Only cache actual page responses, not redirects or errors
    if (
        response.ok &&
        response.status === 200 &&
        response.type !== "opaqueredirect"
    ) {
        const clone = response.clone();
        const text = await clone.text();
        if (text.length > 500) {
            const headers = new Headers(response.headers);
            headers.set("sw-cached-at", Date.now().toString());
            const responseToCache = new Response(text, {
                status: response.status,
                statusText: response.statusText,
                headers,
            });
            cache.put(request, responseToCache);
        }
    }
    return response;
}
