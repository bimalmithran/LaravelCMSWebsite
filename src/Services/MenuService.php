<?php
namespace App\Services;

class MenuService
{
    private $menus;
    private $state = 0;

    /**
     * Constructor.
     *
     * @param array $menus
     */
    public function __construct(array $menus)
    {
        $this->menus = $menus;
    }

    /**
     * Build the menu view.
     *
     * @param array $menus
     * @return string
     */
    public function buildMenuView(array $menus = []): string
    {
        $class = "class='hm-dropdown'";
        if (count($menus) === 0) {
            $menus = $this->menus;
            $class = "";
        }
        $html = "<ul " . $class . ">";
        foreach ($menus as $menu) {
            // echo $menu["name"] . ": " . $menu["menu_type"] . "<br>";
            $html .= $this->buildMenuList($menu);
        }
        $html .= "</ul>";
        return $html;
    }

    /**
     * Build the menu list.
     *
     * @param array $menu
     * @return string
     */
    public function buildMenuList(array $menu): string
    {
        $href =
            "<a href='#'>
         " .
            $menu["name"] .
            "
        </a>";
        if ($menu["menu_type"] === "link") {
            $href =
                "<a href='" .
                $menu["page"]["slug"] .
                ".php'>
         " .
                $menu["name"] .
                "
        </a>";
        }

        $ul = "";
        if ($menu["menu_type"] === "dropdown") {
            // echo "inside dropdown if <br>";
            $this->state++;
            $ul = $this->buildMenuView($menu["children"]);
        }

        $html =
            "<li>
            " .
            $href .
            $ul .
            "
            </li>";

        // echo "<hr>";

        return $html;
    }

    /**
     * Build the mobile menu HTML (offcanvas navigation).
     *
     * Renders the same menu tree using the mobile-specific markup:
     * mm-text spans, sub-menu classes, and menu-item-has-children markers.
     *
     * @param  array  $menus  Defaults to the menus passed in the constructor.
     * @return string
     */
    public function buildMobileMenuView(array $menus = []): string
    {
        if (count($menus) === 0) {
            $menus = $this->menus;
        }

        $html = "<ul class='mobile-menu'>";
        foreach ($menus as $menu) {
            $html .= $this->buildMobileMenuList($menu);
        }
        $html .= "</ul>";

        return $html;
    }

    /**
     * Build a single mobile menu list item, recursing into children.
     *
     * @param  array  $menu
     * @return string
     */
    private function buildMobileMenuList(array $menu): string
    {
        $hasChildren = $menu['menu_type'] === 'dropdown' && !empty($menu['children']);

        $liClass = $hasChildren ? "class='menu-item-has-children'" : '';

        $href = $menu['menu_type'] === 'link' && !empty($menu['page']['slug'])
            ? htmlspecialchars($menu['page']['slug'], ENT_QUOTES) . '.php'
            : '#';

        $label = htmlspecialchars($menu['name'], ENT_QUOTES);

        $anchor = "<a href='{$href}'><span class='mm-text'>{$label}</span></a>";

        $subMenu = '';
        if ($hasChildren) {
            $subMenu = "<ul class='sub-menu'>";
            foreach ($menu['children'] as $child) {
                $subMenu .= $this->buildMobileMenuList($child);
            }
            $subMenu .= "</ul>";
        }

        return "<li {$liClass}>{$anchor}{$subMenu}</li>";
    }
}
