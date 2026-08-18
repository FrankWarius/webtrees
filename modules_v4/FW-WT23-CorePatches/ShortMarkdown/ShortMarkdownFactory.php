<?php

/**
 * FW-WT23-CorePatches
 *
 * Ablage: modules_v4/FW-WT23-CorePatches/ShortMarkdown/ShortMarkdownFactory.php
 */

declare(strict_types=1);

namespace FrankWarius\CorePatches;

use Fisharebest\Webtrees\CommonMark\XrefExtension;
use Fisharebest\Webtrees\Factories\MarkdownFactory;
use Fisharebest\Webtrees\Tree;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Inline\NewlineParser;
use League\CommonMark\Renderer\Block\DocumentRenderer;
use League\CommonMark\Renderer\Block\ParagraphRenderer;
use League\CommonMark\Renderer\Inline\NewlineRenderer;
use League\CommonMark\Renderer\Inline\TextRenderer;
use League\CommonMark\Util\HtmlFilter;

use function strip_tags;
use function strtr;

/**
 * Markdown-Factory mit gekürzter Linkdarstellung.
 *
 * Nur autolink() ist überschrieben. markdown() wird unverändert geerbt und
 * verhält sich wie im Kern.
 */
final class ShortMarkdownFactory extends MarkdownFactory
{
    /**
     * Wie im Kern, ergänzt um die Konfiguration der ExternalLinkExtension.
     */
    protected const array CONFIG_AUTOLINK = [
        'allow_unsafe_links' => false,
        'html_input'         => HtmlFilter::ESCAPE,
        'renderer'           => [
            'soft_break' => self::BREAK,
        ],
        'external_link'      => [
            'open_in_new_window' => true,
            'nofollow'           => 'external',
            'noopener'           => 'external',
            'noreferrer'         => '',
        ],
    ];

    /**
     * Nachbildung von MarkdownFactory::autolink() mit zwei Abweichungen:
     * eigener Renderer für Link-Knoten und zusätzlich die
     * ExternalLinkExtension.
     *
     * ACHTUNG: Diese Methode spiegelt den Kern. Ändert webtrees dort etwas,
     * muss hier nachgezogen werden.
     */
    public function autolink(string $markdown, Tree|null $tree = null): string
    {
        // Minimaler CommonMark-Prozessor, nur für automatische Verlinkung.
        $umgebung = new Environment(static::CONFIG_AUTOLINK);
        $umgebung->addInlineParser(new NewlineParser());
        $umgebung->addRenderer(Document::class, new DocumentRenderer());
        $umgebung->addRenderer(Paragraph::class, new ParagraphRenderer());
        $umgebung->addRenderer(Text::class, new TextRenderer());
        $umgebung->addRenderer(Link::class, new ShortLinkRenderer());
        $umgebung->addRenderer(Newline::class, new NewlineRenderer());
        $umgebung->addExtension(new AutolinkExtension());
        $umgebung->addExtension(new ExternalLinkExtension());

        // Verweise auf andere Datensätze, sofern ein Stammbaum bekannt ist.
        if ($tree instanceof Tree) {
            $umgebung->addExtension(new XrefExtension($tree));
        }

        $konverter = new MarkdownConverter($umgebung);

        $html = $konverter->convert($markdown)->getContent();

        // Nur bestimmte Auszeichnungen zulassen.
        $html = strip_tags($html, ['a', 'br', 'p']);

        // Die Bibliothek fügt Zeilenumbrüche ein, die hier nicht gebraucht werden.
        return strtr($html, ["\n" => '']);
    }
}
