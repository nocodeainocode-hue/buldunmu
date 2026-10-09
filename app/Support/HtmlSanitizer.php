<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Firma sahiplerinin yazdığı zengin metni güvenli bir etiket listesiyle sınırlar.
 * Script, iframe, olay öznitelikleri (onclick vb.) ve javascript: bağlantıları temizlenir.
 */
class HtmlSanitizer
{
    private const ALLOWED = [
        'p', 'br', 'hr', 'strong', 'b', 'em', 'i', 'u', 's', 'del', 'mark', 'sub', 'sup',
        'ul', 'ol', 'li', 'blockquote', 'pre', 'code', 'a', 'span',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
    ];

    private const DROP_WITH_CONTENT = [
        'script', 'style', 'iframe', 'object', 'embed', 'noscript', 'template', 'svg', 'math',
        'form', 'input', 'button', 'textarea', 'select', 'option', 'link', 'meta', 'base', 'head', 'title',
        'audio', 'video', 'source', 'canvas', 'frame', 'frameset', 'applet',
    ];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        // Etiket içermeyen düz metin: yalnızca kaçır (mevcut &amp; gibi varlıkları bozma).
        if (! str_contains($html, '<')) {
            return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
        }

        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->loadHTML('<?xml encoding="UTF-8"><div id="sanitizer-root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('sanitizer-root');

        if (! $root) {
            return htmlspecialchars(strip_tags($html), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
        }

        self::walk($root);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        // Bölünmez boşluk varlık olarak kalsın (metin karşılaştırmaları değişmesin).
        return trim(str_replace("\u{00A0}", '&nbsp;', $output));
    }

    private static function walk(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if ($node instanceof DOMElement) {
                $tag = strtolower($node->tagName);

                if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                    $parent->removeChild($node);

                    continue;
                }

                self::walk($node);

                if (! in_array($tag, self::ALLOWED, true)) {
                    // Bilinmeyen etiket: kendisini sil, içeriğini koru.
                    while ($node->firstChild) {
                        $parent->insertBefore($node->firstChild, $node);
                    }
                    $parent->removeChild($node);

                    continue;
                }

                self::cleanAttributes($node, $tag);

                continue;
            }

            if ($node->nodeType !== XML_TEXT_NODE) {
                $parent->removeChild($node); // yorum, CDATA, işlem yönergesi
            }
        }
    }

    private static function cleanAttributes(DOMElement $element, string $tag): void
    {
        $href = $tag === 'a' ? $element->getAttribute('href') : '';

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $element->removeAttributeNode($attribute);
        }

        if ($tag !== 'a') {
            return;
        }

        $compact = preg_replace('/[\x00-\x20]+/', '', $href) ?? '';

        if ($compact !== '' && preg_match('#^(https?:|mailto:|tel:|/(?!/)|\#)#i', $compact)) {
            $element->setAttribute('href', trim($href));
            $element->setAttribute('rel', 'nofollow noopener ugc');
        }
    }
}
