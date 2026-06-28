<?php

declare(strict_types=1);

namespace MailWritter\Components;

class Column extends BaseComponent
{
    /**
     * @param array<ComponentInterface> $children
     */
    public function __construct(
        protected int $widthPercent,
        protected array $children = []
    ) {
        // Validar que todos los elementos iniciales sean componentes válidos
        foreach ($this->children as $child) {
            if (!$child instanceof ComponentInterface) {
                throw new \InvalidArgumentException('Todos los elementos iniciales de una columna deben implementar ComponentInterface.');
            }
        }
    }

    /**
     * Agrega un componente hijo a la columna.
     */
    public function add(ComponentInterface $component): self
    {
        $this->children[] = $component;
        return $this;
    }

    /**
     * Renderiza la columna y sus hijos dentro de un tag <td>.
     */
    public function render(): string
    {
        $childrenHtml = '';
        foreach ($this->children as $child) {
            $childrenHtml .= $child->render();
        }

        $stylesStr = $this->compileStyles();
        $styleAttr = $stylesStr !== '' ? ' ' . $stylesStr : '';

        return sprintf(
            '<td width="%d%%" valign="top"%s>%s</td>',
            $this->widthPercent,
            $styleAttr,
            $childrenHtml
        );
    }
}
