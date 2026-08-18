<?php

/**
 * FW-WT23-CorePatches
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/ShortMarkdown/ShortLinkRenderer.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\RegexHelper;
use Stringable;

use function parse_url;

/**
 * Renderer für Link-Knoten. Verhält sich wie der Standard-Renderer, zeigt als
 * Linktext aber nicht die vollständige Adresse, sondern nur Schema, Host und
 * Pfad.
 */
final class ShortLinkRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): Stringable|string|null
    {
        Link::assertInstanceOf($node);

        /** @var Link $node */
        $attribute = $node->data->get('attributes');

        if (!RegexHelper::isLinkPotentiallyUnsafe($node->getUrl())) {
            $attribute['href'] = $node->getUrl();
        }

        $titel = $node->getTitle();

        if ($titel !== null) {
            $attribute['title'] = $titel;
        }

        // Beim Öffnen in einem neuen Fenster gehört rel gesetzt, falls die
        // Extension es nicht schon getan hat.
        if (($attribute['target'] ?? null) === '_blank' && !isset($attribute['rel'])) {
            $attribute['rel'] = 'noopener noreferrer';
        }

        return new HtmlElement('a', $attribute, $this->shortenUrlToBasePath($node->getUrl()));
    }

    /**
     * Liefert die Adresse ohne Abfragezeichenfolge und ohne Fragment.
     * Lässt sich die Adresse nicht zerlegen, bleibt sie unverändert.
     */
    private function shortenUrlToBasePath(string $url): string
    {
        $bestandteile = parse_url($url);

        if ($bestandteile === false || !isset($bestandteile['host'])) {
            return $url;
        }

        $schema = $bestandteile['scheme'] ?? 'https';
        $pfad   = $bestandteile['path'] ?? '';

        return $schema . '://' . $bestandteile['host'] . $pfad;
    }
}
