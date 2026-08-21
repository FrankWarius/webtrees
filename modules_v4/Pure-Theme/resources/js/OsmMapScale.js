// Haengt an jede Leaflet-Karte einen Massstabsbalken. addInitHook laeuft bei
// jeder Karte, die nach dieser Datei erzeugt wird - die Karteninstanz wird
// nicht gebraucht.
//
// Eingebunden ueber PureTheme::bodyContent(), also nach vendor.min.js und
// webtrees.min.js im Layout.

if (window.L !== undefined) {
    L.Map.addInitHook(function () {
        L.control.scale({imperial: false}).addTo(this);
    });
}