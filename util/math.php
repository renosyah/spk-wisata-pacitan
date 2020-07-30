<?php 

// ini adalah fungsi untuk mencari nilai terbesar dari data array
function max_attribute_in_array($array, $prop) {
    return max(array_map(function($o) use($prop) {return $o->$prop;},$array));
}

// ini adalah fungsi untuk mencari nilai terkecil dari data array
function min_attribute_in_array($array, $prop) {
    return min(array_map(function($o) use($prop) {return $o->$prop;},$array));
}

?>