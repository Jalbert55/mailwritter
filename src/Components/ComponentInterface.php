<?php

declare(strict_types=1);

namespace MailWritter\Components;

interface ComponentInterface
{
    /**
     * Renderiza el componente a su representación HTML.
     */
    public function render(): string;

    /**
     * Aplica estilos en línea al componente.
     *
     * @param array<string, string> $styles
     */
    public function style(array $styles): self;
}
