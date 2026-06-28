<?php

declare(strict_types=1);

namespace MailWritter\Utils;

class MarkdownParser
{
    /**
     * Parsea un subconjunto simple de Markdown (negritas, cursivas y saltos de línea) a HTML seguro.
     */
    public static function parse(string $text): string
    {
        // Sanitizar el texto de entrada para evitar problemas de HTML malformado o XSS
        $html = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        // Reemplazar **texto** por <strong>texto</strong>
        $html = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $html);

        // Reemplazar *texto* por <em>texto</em>
        $html = preg_replace('/\*(.*?)\*/s', '<em>$1</em>', $html);

        // Reemplazar saltos de línea (\r\n, \r, \n) por <br />
        $html = str_replace(["\r\n", "\r", "\n"], '<br />', $html);

        return $html;
    }
}
