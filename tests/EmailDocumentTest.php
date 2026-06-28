<?php

declare(strict_types=1);

namespace MailWritter\Tests;

use PHPUnit\Framework\TestCase;
use MailWritter\EmailDocument;
use MailWritter\Components\Text;
use MailWritter\Components\Button;
use MailWritter\Utils\MarkdownParser;

class EmailDocumentTest extends TestCase
{
    /**
     * Prueba el funcionamiento del parseador de Markdown utilitario.
     */
    public function testMarkdownParsing(): void
    {
        // Negritas
        $this->assertEquals(
            'Hola <strong>amigo</strong>',
            MarkdownParser::parse('Hola **amigo**')
        );

        // Cursivas
        $this->assertEquals(
            'Texto en <em>cursiva</em>',
            MarkdownParser::parse('Texto en *cursiva*')
        );

        // Saltos de línea
        $this->assertEquals(
            'Línea 1<br />Línea 2',
            MarkdownParser::parse("Línea 1\nLínea 2")
        );

        // Combinado
        $this->assertEquals(
            '<strong>Negrita</strong> y <em>Cursiva</em><br />Nueva linea',
            MarkdownParser::parse("**Negrita** y *Cursiva*\nNueva linea")
        );
    }

    /**
     * Prueba la compilación y aplicación de estilos dinámicos inline.
     */
    public function testComponentStylesCompilation(): void
    {
        $text = new Text('Hola Mundo');
        $text->style([
            'color' => '#333333',
            'font-size' => '16px',
        ]);

        $html = $text->render();

        // Debe contener exactamente el atributo style compilado
        $this->assertStringContainsString('style="color: #333333; font-size: 16px;"', $html);
    }

    /**
     * Prueba la integración del documento y la generación del Skeleton HTML completo.
     */
    public function testEmailDocumentGeneration(): void
    {
        $email = new EmailDocument(
            'Mi Email de Prueba',
            '#e0e0e0',
            700
        );

        $text = new Text('Este es un texto con **negrita**', 'center', true);
        $button = new Button('Hacer Click', 'https://example.com', '#ff0000', '#ffffff');

        $email->add($text);
        $email->add($button);

        $html = $email->build();

        // 1. Verificar esqueleto HTML básico
        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('email-container', $html);
        
        // 2. Título esperado
        $this->assertStringContainsString('<title>Mi Email de Prueba</title>', $html);

        // 3. Variables de configuración aplicadas
        $this->assertStringContainsString('background-color: #e0e0e0;', $html);
        $this->assertStringContainsString('width="700"', $html);
        $this->assertStringContainsString('max-width: 700px', $html);

        // 4. Salida del componente Text (con Markdown renderizado y alineación por defecto)
        $this->assertStringContainsString('style="text-align: center;"', $html);
        $this->assertStringContainsString('Este es un texto con <strong>negrita</strong>', $html);

        // 5. Salida del componente Button (con tabla y enlace correspondientes)
        $this->assertStringContainsString('bgcolor="#ff0000"', $html);
        $this->assertStringContainsString('href="https://example.com"', $html);
        $this->assertStringContainsString('color: #ffffff;', $html);
        $this->assertStringContainsString('Hacer Click', $html);
    }
}
