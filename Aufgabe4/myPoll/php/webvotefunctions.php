<?php // functions for webvote sample

// bekommt ein array und ein Schlüsselwort
function array_item($ar, $key)
{
    //wenn das erste Argument ein Array
    //ist und das Schlüsselwort im Array enthalten ist
    if (is_array($ar) && array_key_exists($key, $ar)) {
        // Gebe das Array an der Position (des Schlüsselwortes zurück)
        return ($ar[$key]);
    } else {
        return FALSE;
    }
}

// html header
function html_start($title = "no title", $css = FALSE)
{
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"',
    '"http://www.w3.org/TR/html4/loose.dtd">', "\n",
    '<html><head>',
    '<meta http-equiv="Content-Type"',
    '      content="text/html; charset=iso-8859-1" />', "\n";
    if ($css)
        printf('<link href="%s" rel="stylesheet" type="text/css">', $css);
    echo "<title>$title</title>\n</head><body>\n";
}


// html footer
function html_end()
{

    echo ' <!-- jQuery -->';
    echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>', "\n";
    echo '<!-- Bootstrap JavaScript -->', "\n";
    echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>', "\n";
    echo '</body></html>', "\n";
}

?>
