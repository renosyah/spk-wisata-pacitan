<?php 

function max_attribute_in_array($array, $prop) {
    return max(array_map(function($o) use($prop) {return $o->$prop;},$array));
}


function min_attribute_in_array($array, $prop) {
    return min(array_map(function($o) use($prop) {return $o->$prop;},$array));
}



?>