<?php

declare(strict_types=1);

namespace MailWritter\Components;

use MailWritter\Utils\MarkdownParser;

class Text extends BaseComponent
{
    /**
     * Usa Constructor Property Promotion de PHP 8.
     */
    public function __construct(
        protected string $content,
        protected string $align = 'left',
        protected bool $parseMarkdown = false
    ) {
    }

    /**
     * Renderiza el componente Text envuelto en un <div> con alineación y estilos aplicados.
     */
    public function render(): string
    {
        // Guardamos los estilos originales para evitar efectos secundarios
        $originalStyles = $this->styles;

        // Inyectamos la alineación de texto a menos que ya se haya definido un text-align en style()
        if (!isset($this->styles['text-align'])) {
            $this->styles['text-align'] = $this->align;
        }

        $stylesStr = $this->compileStyles();

        // Restauramos los estilos originales
        $this->styles = $originalStyles;

        $processedContent = $this->parseMarkdown
            ? MarkdownParser::parse($this->content)
            : htmlspecialchars($this->content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        // Formateamos el div con los estilos inline correspondientes
        $styleAttr = $stylesStr !== '' ? ' ' . $stylesStr : '';

        return sprintf('<div%s>%s</div>', $styleAttr, $processedContent);
    }
}
