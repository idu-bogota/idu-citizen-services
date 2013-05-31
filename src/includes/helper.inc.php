<?php
//Helper
function is_mobile() {
    global $is_mobile;
    if($is_mobile === null) {
        $is_mobile = (bool)preg_match('#\b(ip(hone|od)|android\b.+\bmobile|opera m(ob|in)i|windows (phone|ce)|blackberry'.
                        '|s(ymbian|eries60|amsung)|p(alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
                        '|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT'] );
    }
    return $is_mobile;
}

function get_menu_item_class($selected, $item_id) {
    if($selected == $item_id) {
        return 'class = "active"';
    }
}
