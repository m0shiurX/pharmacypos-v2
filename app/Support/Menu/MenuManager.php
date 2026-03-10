<?php

namespace App\Support\Menu;

/**
 * Lightweight drop-in replacement for Nwidart\Menus.
 * Provides the exact API used in this POS application.
 */
class MenuManager
{
    protected array $menus = [];
    protected array $presenters = [];

    public function create(string $name, callable $callback): MenuBuilder
    {
        $builder = new MenuBuilder();
        $callback($builder);
        $this->menus[$name] = $builder;
        return $builder;
    }

    public function modify(string $name, callable $callback): void
    {
        if (isset($this->menus[$name])) {
            $callback($this->menus[$name]);
        }
    }

    public function render(string $name, string $presenter = 'default'): string
    {
        if (!isset($this->menus[$name])) {
            return '';
        }

        $presenterClass = $this->presenters[$presenter] ?? null;
        if ($presenterClass && class_exists($presenterClass)) {
            $presenterInstance = new $presenterClass();
            return $presenterInstance->render($this->menus[$name]);
        }

        return $this->menus[$name]->renderDefault();
    }

    public function setPresenter(string $name, string $class): void
    {
        $this->presenters[$name] = $class;
    }

    public function get(string $name): ?MenuBuilder
    {
        return $this->menus[$name] ?? null;
    }
}
