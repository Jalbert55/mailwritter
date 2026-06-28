<?php

declare(strict_types=1);

namespace MailWritter;

use MailWritter\Components\ComponentInterface;

class EmailDocument
{
    /**
     * @var array<ComponentInterface>
     */
    protected array $components = [];

    /**
     * Usa Constructor Property Promotion de PHP 8.
     */
    public function __construct(
        protected string $title,
        protected string $bodyBgColor = '#f6f6f6',
        protected int $maxWidth = 600
    ) {
    }

    /**
     * Añade un componente al documento de correo.
     */
    public function add(ComponentInterface $component): self
    {
        $this->components[] = $component;
        return $this;
    }

    /**
     * Genera el HTML final imbatible del correo inyectando los componentes agregados en el Skeleton HTML.
     */
    public function build(): string
    {
        $componentsHtml = '';
        foreach ($this->components as $component) {
            $componentsHtml .= $component->render();
        }

        // Estructura HTML estructurada tradicional requerida
        return sprintf(
            '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>%s</title>
    <style>
        /* Reset de estilos básicos para consistencia */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* Estilos responsivos */
        @media screen and (max-width: %dpx) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .fluid { width: 100% !important; max-width: 100% !important; height: auto !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; width: 100% !important; background-color: %s;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="%s">
        <tr>
            <td align="center" style="padding: 20px 10px;">
                <table class="email-container" width="%d" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="margin: 0 auto; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 20px; font-family: sans-serif;">
                            %s
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>',
            htmlspecialchars($this->title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $this->maxWidth,
            htmlspecialchars($this->bodyBgColor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            htmlspecialchars($this->bodyBgColor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $this->maxWidth,
            $componentsHtml
        );
    }
}
