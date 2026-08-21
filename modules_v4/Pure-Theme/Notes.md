# Pure-Theme — Notizen

Begleittext zu `resources/css/Pure.css` und `resources/css/Patch-23.css`.
Die Stylesheets werden über die Asset-Route öffentlich ausgeliefert, deshalb
stehen die Begründungen hier und nicht dort.

Ablage: `modules_v4/Pure-Theme/Theme-Notizen.md` — im Modulwurzelverzeichnis,
also nicht über die Asset-Route erreichbar.

Die Abschnitte folgen der Reihenfolge in den Stylesheets.

## Aufteilung und Ladereihenfolge

`Pure.css` enthält Gestaltung: alles, was so bleiben soll, auch wenn webtrees
sich ändert.

`Patch-23.css` enthält nur Regeln, die es gibt, weil der Kern in 2.3.0 etwas
falsch macht. Sie verschwinden ersatzlos, sobald der jeweilige Fehler oben
behoben ist. Beim Sprung auf 2.4 gehört die Datei komplett auf den Prüfstand.

Im Stylesheet steht je Block nur die betroffene Vorlage. Der Stand der Meldung
steht hier.

```php
$stylesheets[] = $this->assetUrl('css/Pure.css');
$stylesheets[] = $this->assetUrl('css/Patch-23.css');
```

## Grundlagen: `[dir]` und Spezifität

`[dir]` trifft jedes Element mit einem `dir`-Attribut. In webtrees trägt `<html>`
ein `dir="ltr"`, also passt `[dir] td` praktisch auf jede Tabellenzelle.

Der RTL-Bau von Bootstrap stellt jeder richtungsabhängigen Regel `[dir]` voran,
damit sich links und rechts spiegeln lassen. Jedes `[dir]` hebt die Spezifität
um eine Klassenstufe. Eine eigene Regel ohne `[dir]` verliert deshalb gegen die
Vendor-Regel, auch wenn sie später steht.

Beispiel Zellabstand:

- Vendor: `[dir] .table > :not(caption) > * > *` — 0,2,1
- Eigen: `[dir] table:not([cellpadding]) td` — 0,2,2, gewinnt

---

# Pure.css

## Farbschema

Ausschließlich hell. Das Theme muss dazu `bootstrapColorScheme()` mit `light`
überschreiben.

## Angleichung an warius.info

Übernommen wurden Schriftfamilie, Überschriftengewicht 700, Laufweite
`-0.02em`, die Hover-Farbe `#104e8b` und `tbody { vertical-align: top }`.

Größen und Abstände bleiben aus der bisherigen Fassung — die Oberfläche ist
datendicht, nicht fließtextlastig.

## Schrift Inter

Selbst gehostet statt über Google Fonts, damit keine Anfrage an Dritte entsteht.
Quelle: rsms/inter, SIL Open Font License 1.1.

Im Ordner `resources/fonts` liegen vier Dateien:

- `Inter-Regular.woff2` und `Inter-Bold.woff2` — die beiden benutzten Schnitte,
  eingebunden über `@font-face`
- `InterVariable.woff2` und `InterVariable-Italic.woff2` — Reserve, falls
  weitere Gewichte gebraucht werden

Die übrigen 34 Dateien der Auslieferung wurden gelöscht.

Bei genau zwei Gewichten sind die statischen Schnitte zusammen etwa 220 kB, die
variable Datei allein rund 340 kB. Der Wechsel lohnt erst ab drei Gewichten oder
wenn die optische Größe genutzt werden soll.

**Pfade:** Modul-Assets werden über
`/module/<name>/Asset?asset=<urlkodierter Pfad>&hash=…` ausgeliefert. Relative
Pfade wie `../fonts/` lösen gegen `/module/_Pure-Theme_/` auf und laufen ins
Leere. Deshalb `url("Asset?asset=fonts%2FInter-Regular.woff2")`.

## Grundwerte

`--bs-body-line-height: 1.05` steht bewusst ohne Einheit. Eine Prozentangabe
wird zu einem festen Betrag berechnet und so vererbt; einheitenlos rechnet jedes
Element mit seiner eigenen Schriftgröße.

Die Regel `body { font-family; line-height }` wiederholt, was schon in `:root`
als Bootstrap-Variable steht. Sie ist trotzdem nötig, weil `vendor.min.css`
beides direkt am `body` setzt und damit Bootstraps variablengesteuerte Regel
überschreibt.

`[dir] body { margin: 0 }` entfernt einen Rand, der aus der Inhalts-CSS von
TinyMCE stammt und als `[dir] body { margin: 1rem }` global wirkt. Ein einfaches
`body { margin: 0 }` verliert dagegen, weil `[dir]` eine Klassenstufe zählt.

## Kopfbereich

Trennlinie unter dem Kopf, sonst unverändert.

## Überschriften

Gewicht 700 und Laufweite `-0.02em` von warius.info. Die Größen in Prozent
stammen unverändert aus der bisherigen Fassung.

## Fließtext

Engere Abstände über und unter Absätzen, passend zur datendichten Oberfläche.

## Tabellen

`border-collapse` und geerbte Rahmen, damit die Linien nicht doppelt stehen.
`tbody { vertical-align: top }` von warius.info.

Der Zellabstand `0.2rem 0.4rem` muss die Vendor-Regel übertreffen, siehe
Grundlagen oben. Zum aktuellen Zustand dieser Regel siehe „Offene Punkte".

## Karten

Die Kurzform von `padding` erwartet `y x`. In der Vorgängerfassung waren die
Achsen vertauscht — vorher 0.2rem oben und unten und 0 links und rechts, jetzt
umgekehrt. Wirkt der Kartenkopf dadurch zu flach, entweder `--bs-gutter-y`
anheben oder feste Werte setzen.

## Raster

Enge Rinnen: `--bs-gutter-x: 0.4rem`, `--bs-gutter-y: 0`.

## Akkordeon

Gleiche Achsenkorrektur wie beim Kartenkopf.

## DataTables 2

`div.dt-container div.dt-layout-full > :only-child` zielt auf die
Markup-Struktur von DataTables 2. Mit dem vorbereiteten Wechsel auf 3.0.0 können
sich die Klassennamen ändern.

## webtrees-eigene Bausteine

`.wt-page-title` steht unverändert bei 650, während die Überschriften auf 700
stehen. Die Regel gewinnt weiterhin, weil sie später steht und gleich spezifisch
ist. Auf 700 anheben, wenn der Seitentitel zu den übrigen Überschriften passen
soll.

Die Reihenfolge im Kopf — Titel, Suche, zweite Navigation — wird über `order`
und `flex` gesetzt, nicht über das Markup.

## Schaltflächen

Kompakte Innenabstände, Schriftwerte aus den Grundwerten übernommen.

## Statistikblock

`modules/gedcom-stats/statistics.phtml`

Die Zahlen der linken Tabelle stehen rechtsbündig. `text-align: end` statt
`right`, damit es bei rechtsläufigen Sprachen mitdreht.

Die zugehörige Spaltenbreite steht in `Patch-23.css`, weil sie einen Fehler im
Kern ausgleicht. Die Aufteilung auf zwei Dateien ist gewollt: `text-align` bleibt
auch nach einem Upstream-Fix.

## Nachnamenliste

`lists/surnames-table.phtml`

Die Vorlage setzt an der Zahlenspalte die Bootstrap-Utility `text-center`.
Utilities arbeiten mit `!important`, deshalb ist hier ohne `!important` nichts
auszurichten.

## Nachrichtenblock

`modules/user-messages/user-messages.phtml`

Bei `table-layout: auto` sind Prozentwerte nur Wünsche: der Browser rechnet
zuerst die Mindestbreiten aus dem Inhalt, und der lange Betreff setzt sich
durch. Bei festem Layout zählen nur die Breiten der ersten Zeile, und sie müssen
zusammen 100 Prozent ergeben — daher 10/45/20/25.

`overflow-wrap: anywhere` in der letzten Spalte verhindert, dass lange Adressen
in die Nachbarspalte laufen. Ohne das erzwingt die Mindestbreite der längsten
Adresse eine breitere Spalte, und die Prozentwerte greifen nicht.

---

# Patch-23.css

## Medienliste

`modules/media-list/page.phtml` — gemeldet als **#5469**

Der Kern legt die verlinkten Datensätze in den `card-footer`. Zusammen mit
`card h-100` werden alle Karten einer Zeile auf gleiche Höhe gezogen, der Body
wächst mit, und zwischen Bilddaten und Verweisen entsteht eine große Leerfläche.
Bei Medienobjekten mit 30 bis 100 Verweisen sind das mehrere hundert Pixel.

Hier wächst der Body nicht mehr, und der Footer verliert Grund und Trennlinie,
damit die Naht nicht sichtbar bleibt. Der verbleibende Leerraum sitzt dann unten
in der Karte, wo er nicht stört.

## Statistikblock

`modules/gedcom-stats/statistics.phtml` — nicht gemeldet

Die Vorlage gibt beiden Spalten `class="col col"`. Damit teilen sie die Zeile
hälftig, obwohl die linke Tabelle schmal ist. Das doppelte `col` ist im Kern
redundant.

Die Regeln entsprechen dem, was `col col-sm-auto` im Markup bewirken würde.
`width: 1%` liegt unter der Mindestbreite, die Zahlenspalte fällt dadurch auf
Inhaltsbreite zurück und die Beschriftungsspalte bekommt den Rest.

---

# Offene Punkte

## Verrutschter Selektor beim Zellabstand

In `Pure.css`, Abschnitt Tabellen, steht:

```css
[dir] .table> :not(caption)>*>* [dir] table:not([cellpadding]) td,
[dir] table:not([cellpadding]) th {
  padding: 0.2rem 0.4rem;
}
```

Der erste Zweig kann nichts treffen: das zweite `[dir]` verlangt ein Element mit
`dir`-Attribut innerhalb der Tabellenzelle und darin nochmals eine Tabelle.
Wirksam ist nur `th`; bei `td` gewinnt weiterhin Bootstrap. Vermutlich ist beim
Zusammenführen der Vendor-Selektor in den eigenen geraten. Gewollt war:

```css
[dir] table:not([cellpadding]) td,
[dir] table:not([cellpadding]) th {
  padding: 0.2rem 0.4rem;
}
```

Nicht geändert — offen zur Entscheidung.

## Meldung zum Statistikblock

Das doppelte `col col` und die daraus folgende Spaltenaufteilung sind noch nicht
bei fisharebest/webtrees gemeldet.