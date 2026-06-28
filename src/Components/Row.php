<?php

declare(strict_types=1);

namespace MailWritter\Components;

class Row extends BaseComponent
{
    /**
     * @param array<Column> $columns
     */
    public function __construct(protected array $columns)
    {
        // Validación estricta para garantizar que solo se pasen columnas
        foreach ($columns as $column) {
            if (!$column instanceof Column) {
                throw new \InvalidArgumentException('Todos los elementos de Row deben ser instancias de Column.');
            }
        }
    }

    /**
     * Renderiza la fila envolviendo las columnas en una tabla compatible.
     */
    public function render(): string
    {
        $columnsHtml = '';
        foreach ($this->columns as $column) {
            $columnsHtml .= $column->render();
        }

        // Configuración de estilos base para forzar comportamiento en tablas responsivas
        $baseStyles = [
            'table-layout' => 'fixed',
        ];

        // Guardamos los estilos originales de la instancia
        $originalStyles = $this->styles;

        // Combinamos
        $this->styles = array_merge($baseStyles, $this->styles);
        $styleStr = $this->compileStyles();

        // Restauramos
        $this->styles = $originalStyles;

        $styleAttr = $styleStr !== '' ? ' ' . $styleStr : '';

        return sprintf(
            '<table width="100%" border="0" cellspacing="0" cellpadding="0"%s><tr>%s</tr></table>',
            $styleAttr,
            $columnsHtml
        );
    }
}
