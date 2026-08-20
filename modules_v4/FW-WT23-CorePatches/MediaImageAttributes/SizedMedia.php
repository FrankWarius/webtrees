<?php

/**
 * Medienobjekt, das SizedMediaFile statt MediaFile liefert.
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/MediaImageAttributes/SizedMedia.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\Fact;
use Fisharebest\Webtrees\Media;
use Fisharebest\Webtrees\MediaFile;
use Illuminate\Support\Collection;

class SizedMedia extends Media
{
    /**
     * Einziger Erzeugungspunkt von MediaFile im Kern. firstImageFile() und
     * displayImage() greifen beide hierauf zurueck.
     *
     * @return Collection<int,MediaFile>
     */
    public function mediaFiles(): Collection
    {
        return $this->facts(['FILE'])
            ->map(fn (Fact $fact): MediaFile => new SizedMediaFile($fact->gedcom(), $this));
    }
}
