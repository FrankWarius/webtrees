<?php

/**
 * Mediendatei, deren Bilder eine Hoehe und loading="lazy" mitbekommen.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/MediaImageAttributes/SizedMediaFile.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\MediaFile;

class SizedMediaFile extends MediaFile
{
    /**
     * displayImage() im Kern baut die Attribute als
     * "$image_attributes + [Vorgaben]" zusammen. Beim +-Operator gewinnt der
     * linke Operand, uebergebene Attribute schlagen also die Vorgaben. Damit
     * genuegt es, sie vorne anzureichern — der Methodenrumpf des Kerns bleibt
     * unangetastet und spaetere Aenderungen von Greg wirken weiter.
     *
     * Kein width: als HTML-Attribut ist "auto" ungueltig und wird verworfen.
     * Die Breite ergibt sich aus dem Seitenverhaeltnis, sobald die Hoehe steht.
     *
     * @param array<string,string> $image_attributes
     */
    public function displayImage(int $width, int $height, string $fit, array $image_attributes = []): string
    {
        return parent::displayImage($width, $height, $fit, $image_attributes + [
            'loading' => 'lazy',
            'height'  => (string) $height,
        ]);
    }
}
