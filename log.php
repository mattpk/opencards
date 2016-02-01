<?php
function console_log( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'php: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'php: " . $data . "' );</script>";

    echo $output;
}
?>