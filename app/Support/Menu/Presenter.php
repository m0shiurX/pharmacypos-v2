<?php

namespace App\Support\Menu;

abstract class Presenter
{
    abstract public function getOpenTagWrapper();

    abstract public function getCloseTagWrapper();

    abstract public function getMenuWithoutDropdownWrapper($item);

    abstract public function getMenuWithDropDownWrapper($item);

    abstract public function getActiveState($item, $state = '');

    public function render(MenuBuilder $builder): string
    {
        $html = $this->getOpenTagWrapper();

        foreach ($builder->getItems() as $item) {
            if ($item->isDropdown()) {
                $html .= $this->getMenuWithDropDownWrapper($item);
            } else {
                $html .= $this->getMenuWithoutDropdownWrapper($item);
            }
        }

        $html .= $this->getCloseTagWrapper();

        return $html;
    }
}
