<?php
function pagination(int $number_of_rows_per_page, int &$show_from_row, int &$number_of_pages, int &$page_num, string $query = "SELECT COUNT(*) FROM `stores`;")
{
    if (!isset($_POST["search"])) {
        $stmt = DB_Connection::getInstance()->prepare($query);
        $stmt->execute();
        $number_of_stores = $stmt->get_result()->fetch_array()[0];

        if (isset($_GET["page_num"])) {
            $page_num = $_GET["page_num"];
        }
        $number_of_pages = ceil($number_of_stores / $number_of_rows_per_page);
        $show_from_row = ($page_num - 1) * $number_of_rows_per_page;
    }
}
