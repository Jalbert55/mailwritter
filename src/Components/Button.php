<?php

declare(strict_types=1);

namespace MailWritter\Components;

class Button extends BaseComponent
{
    /**
     * Usa Constructor Property Promotion de PHP 8.
     */
    public function __construct(
        protected string $text,
        protected string $url,
        protected string $bgColor = '#007bff',
        protected string $textColor = '#ffffff'
    ) {
    }

    /**
     * Renderiza el componente Button utilizando una estructura de tabla pura para máxima compatibilidad.
     */
    public function render(): string
    {
        // Estilos por defecto para el enlace (<a>)
        $defaultAnchorStyles = [
            'color' => $this->textColor,
            'text-decoration' => 'none',
            'font-weight' => 'bold',
            'display' => 'inline-block',
        ];

        // Guardamos los estilos adicionales de la instancia
        $originalStyles = $this->styles;

        // Mezclamos los estilos por defecto con los agregados vía style()
        $this->styles = array_merge($defaultAnchorStyles, $this->styles);
        $anchorStylesStr = $this->compileStyles();

        // Restauramos los estilos originales
        $this->styles = $originalStyles;

        // Sanitización para evitar problemas en atributos HTML
        $safeText = htmlspecialchars($this->text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $safeUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $safeBgColor = htmlspecialchars($this->bgColor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return sprintf(
            '<table border="0" cellspacing="0" cellpadding="0"><tr><td align="center" bgcolor="%s" style="border-radius: 4px; padding: 12px 20px;"><a href="%s" target="_blank" %s>%s</a></td></tr></table>',
            $safeBgColor,
            $safeUrl,
            $anchorStylesStr,
            $safeText
        );
    }
}
