const urlsToCache = ["images/maldevidelogo.svg", "style/main.css", "manifest.json"]
const appCache = "app-files"

self.addEventListener("install", evt => {
  self.skipWaiting()
  evt.waitUntil(
    caches.open(appCache)
      .then(cache => cache.addAll(urlsToCache))
      .catch(err => console.error(err))
  )
})

self.addEventListener("activate", evt => self.clients.claim())

self.addEventListener("fetch", evt => evt.respondWith(
  caches.match(evt.request).then(res => res || fetch(evt.request))
))

/* (C) LOAD WITH NETWORK FIRST, FALLBACK TO CACHE IF OFFLINE
 * self.addEventListener("fetch", evt => evt.respondWith(
 *   fetch(evt.request).catch(() => caches.match(evt.request))
 *   ));*/

