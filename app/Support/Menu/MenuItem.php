<?php

namespace App\Support\Menu;

class MenuItem
{
    public string $title;
    public string $icon;

    protected string $url;
    protected array $attributes;
    protected bool $active = false;
    protected bool $isDropdown = false;
    protected array $children = [];
    protected int $order = 0;

    public function __construct(string $url, string $title, array $attributes = [])
    {
        $this->url = $url;
        $this->title = $title;
        $this->icon = $attributes['icon'] ?? '';
        $this->active = $attributes['active'] ?? false;
        $this->attributes = $attributes;
    }

    public function order(int $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setDropdown(bool $isDropdown): void
    {
        $this->isDropdown = $isDropdown;
    }

    public function isDropdown(): bool
    {
        return $this->isDropdown;
    }

    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    public function getChilds(): array
    {
        return $this->children;
    }

    public function hasActiveOnChild(): bool
    {
        foreach ($this->children as $child) {
            if ($child->isActive()) {
                return true;
            }
            if ($child->hasActiveOnChild()) {
                return true;
            }
        }
        return false;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getAttributes(): string
    {
        $html = [];
        foreach ($this->attributes as $key => $value) {
            if (in_array($key, ['icon', 'active'])) {
                continue;
            }
            if (is_string($value)) {
                $html[] = $key . '="' . e($value) . '"';
            }
        }
        return implode(' ', $html);
    }
}
