window.flutterWebRenderer = "html";

// Allow CORS for image loading
window.addEventListener('load', function() {
    let meta = document.createElement('meta');
    meta.httpEquiv = "Content-Security-Policy";
    meta.content = "default-src * 'unsafe-inline' 'unsafe-eval'; img-src * data: blob: 'unsafe-inline'";
    document.head.appendChild(meta);
});
