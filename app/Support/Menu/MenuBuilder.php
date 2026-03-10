<?php

namespace App\Support\Menu;

class MenuBuilder
{
    protected array $items = [];

    public function url(string $url, string $title, array $attributes = []): MenuItem
    {
        $item = new MenuItem($url, $title, $attributes);
        $this->items[] = $item;
        return $item;
    }

    public function dropdown(string $title, callable $callback, array $attributes = []): MenuItem
    {
        $item = new MenuItem('#', $title, $attributes);
        $item->setDropdown(true);
        $builder = new self();
        $callback($builder);
        $item->setChildren($builder->getItems());
        $this->items[] = $item;
        return $item;
    }

    public function getItems(): array
    {
        $items = $this->items;
        usort($items, fn($a, $b) => $a->getOrder() <=> $b->getOrder());
        return $items;
    }

    public function renderDefault(): string
    {
        $html = '<ul>';
        foreach ($this->getItems() as $item) {
            $html .= '<li><a href="' . e($item->getUrl()) . '">' . e($item->title) . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
