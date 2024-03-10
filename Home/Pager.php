<?php
class Pager {
    public static function getLinks($currentPage, $totalPages) {
        $links = '';

        for ($i = 1; $i < $totalPages; $i++) {
            if ($i == $currentPage) {
                $links .= '<span class="active">' . $i . '</span>';
            } else {
                $links .= '<a href="?page=' . $i . '">' . $i . '</a>';
            }
        }

        return $links;
    }
    
}
?>
