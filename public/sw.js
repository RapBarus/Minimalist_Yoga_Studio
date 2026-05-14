const CACHE_NAME = "minimalist-studio-v1";
const STATIC_CACHE = "minimalist-static-v1";
const DYNAMIC_CACHE = "minimalist-dynamic-v1";

// ── Static assets — Cache-First ──
const STATIC_ASSETS = [
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

    const authRoutes = ["/login", "/register"];
    if (authRoutes.includes(url.pathname)) return;
    if (url.pathname === "/") return;

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
        const offlinePage = await caches.match("/offline");
        if (offlinePage) return offlinePage;
        return new Response("Offline", { status: 503 });
    }
}

// ── Network-First strategy ──
async function networkFirst(request) {
    try {
        const response = await fetch(request);
        // Only cache if response has content and is not a redirect
        if (response.ok && response.status === 200) {
            const clone = response.clone();
            const text = await clone.text();
            if (text.length > 500) {
                // Only cache if has actual content
                const cache = await caches.open(DYNAMIC_CACHE);
                const newResponse = new Response(text, {
                    status: response.status,
                    statusText: response.statusText,
                    headers: response.headers,
                });
                cache.put(request, newResponse);
            }
        }
        return response;
    } catch {
        const cached = await caches.match(request);
        if (cached) return cached;
        const offlinePage = await caches.match("/offline");
        if (offlinePage) return offlinePage;
        return new Response("Offline", { status: 503 });
    }
}
