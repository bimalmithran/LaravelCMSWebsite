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
}
