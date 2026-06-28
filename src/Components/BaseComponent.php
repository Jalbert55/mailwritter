<?php

declare(strict_types=1);

namespace MailWritter\Components;

abstract class BaseComponent implements ComponentInterface
{
    /**
     * @var array<string, string>
     */
    protected array $styles = [];

    /**
     * {@inheritdoc}
     */
    public function style(array $styles): self
    {
        $this->styles = array_merge($this->styles, $styles);
        return $this;
    }

    /**
     * Compila el array de estilos asociativos en una cadena de estilos en línea HTML.
     * Ejemplo: ['color' => '#333', 'font-size' => '16px'] -> style="color: #333; font-size: 16px;"
     */
    protected function compileStyles(): string
    {
        if (empty($this->styles)) {
            return '';
        }

        $compiled = [];
        foreach ($this->styles as $property => $value) {
            $compiled[] = sprintf('%s: %s;', trim($property), trim($value));
        }

        return sprintf('style="%s"', implode(' ', $compiled));
    }
}
