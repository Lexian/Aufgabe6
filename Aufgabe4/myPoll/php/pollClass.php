<?php // functions to build forms
// used by categories.php, titleform.php, search.php

// start form; use table to align form entries
function form_start($action, $head, $head_description)
{

    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="col-md-6 text-center">';
    echo '<h2><i class="glyphicon glyphicon-plus-sign"></i>', $head, '</h2>';
    echo '<h6>', $head_description, '</h6>';
    echo '<br />';
    echo '</div>';
    echo '</div>';
    echo '<div class="row table-bordered">';
    echo '<div class="col-md-4 col-md-offset-4">';
    echo '<div class="user-poll-section table-bordered ">', "\n";
    echo '<form   method="post"', html_attribute("action", $action), ">\n";

}

// close form
function form_end()
{
    echo "</form></div></div></div></div>\n\n";
}

//Form panel header einstellen :D, der bleibt immer gleich das einzige was sich ändert sind die Felder darin
function form_heading_start()
{
    echo '<div class="panel-heading ">', "\n";
}

function form_heading_end()
{
    echo '<hr /></div>', "\n";
}

function form_panel_body_start()
{
    echo '<div class="panel-body">';
}

function    form_panel_body_end()
{
    echo '</div>';
}

/**
 * @param $answerID
 * @param $ansText
 * @param $user_known
 * @param $x
 */
function form_answer($name, $answerID, $ansText, $user_known, $x)
{

    echo ' <div class="radio">';
    echo '<label>', "\n";
    echo '<input ', html_attribute("type", $user_known), '  name="', $name, '" id="inputID" ',
    html_attribute("value", $answerID), ' checked="checked">';
    echo '<strong>', $x, ':)', ' ', $ansText, "\n";
    echo '</label>', "\n";
    echo '</div>', "\n";


}

function form_panel_footer_start()
{
    echo '<div class="panel-footer">';
}

function form_panel_footer_end()
{
    echo '</div>';
}

function form_label($item, $value)
{
    echo '<label><br/><strong>', $value, '</strong>', $item, '</label><br />', "\n";
}


// save hidden data in form
function form_hidden($name, $value)
{
    echo '<input type="hidden" ',
    html_attribute("name", "$name"),
    html_attribute("value", $value),
    " />\n";
}

// create text input field for form
function form_text($pretext, $name, $value, $type)
{

    echo '<strong>', $pretext;
    echo '<input class="form-control" ',
    html_attribute("type", "$type"),
    html_attribute("name", "form[$name]");
    if ($value)
        echo html_attribute("value", $value);
    echo ' /></strong>', "\n";
}


// erstellt eine Liste mit allen Umfragen die momentan zur verfügung stehen
function list_polls_start()
{
    echo '<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-capitalize table-responsive">', "\n";
    echo '<div id="ContentList" class="list-group pre-scrollable">', "\n";
    echo '<a href="#"  class="list-group-item active">alle Umfragen</a>', "\n";
}

function list_polls_end()
{
    echo '</div>', "\n", '</div>';
}

function show_poll_list_view($db)
{

    $sql = "SELECT * FROM poll_form";
    $rows = $db->queryObjectArray($sql);
    if ($rows == FALSE) {
        echo "<p>Es gibt leider noch keine Umfragen.</p>\n";
        return;
    }
    $poll = $rows;

    foreach ($poll as $row) {
        $name = base64_encode($row->pollID);
        echo '<a href=getPollView.php?', html_attribute("title", $name), html_attribute("class", "list-group-item "), '>', $row->title, '</a>';
    }


}

function show_poll_list_result($db)
{

    $sql = "SELECT * FROM poll_form";
    $rows = $db->queryObjectArray($sql);
    if ($rows == FALSE) {
        echo "<p>Es gibt leider noch keine Umfragen.</p>\n";
        return;
    }
    $poll = $rows;

    foreach ($poll as $row) {
        $name = base64_encode($row->pollID);
        echo '<a href=result.php?', html_attribute("pollID", $name), html_attribute("class", "list-group-item "), '>', $row->title, '</a>';
    }


}


// erstellt einen Button
function form_button($name, $bclass, $txt, $type)
{
    echo '<a >';
    echo '<input ',
    html_attribute("class", "btn btn-sm btn-$bclass "),
    html_attribute("type", $type),
    html_attribute("value", $txt),
    html_attribute("name", "form[$name]"),

    '/>', "\n";
    echo '</a>', "\n";
}


function form_back_button($text)
{
    echo '<a >';
    echo '<input ',
    html_attribute("class", "btn btn-sm btn-danger"),
    html_attribute("type", "submit"),
    html_attribute("value", $text),
    html_attribute("name", "dismiss"),

    '/>', "\n";
    echo '</a>', "\n";
}

function form_youtube($source)
{
    echo '<div  class="embed-responsive embed-responsive-4by3 " >', "\n";
    echo '<iframe class="embed-responsive-item" ', html_attribute("src", $source), '></iframe>', "\n";
    echo '</div>', "\n";
}

// build name="value"
function html_attribute($name, $value)
{
    return $name . '="' . htmlspecialchars($value) . '" ';
}

function html_attribute_special($name, $value)
{
    return $name . '=' . $value . '" ';
}

function show_error_msg($txt)
{
    echo '<p><span class="red">', htmlentities($txt), '</span></p>', "\n";
}

function copyfield($linkUrl)
{


    echo ' <strong class="btn-primary">Link</strong>', "\n";
    echo '<input class="table-bordered" ',
    html_attribute("value", $linkUrl),
    html_attribute("name", $linkUrl),
    html_attribute("type","text"),' > </input>';
    echo '<input type="hidden" id="search-submit" />';
    echo '<hr />';


}

function link_item()
{
    echo '<a href="#" type="modal" ></a>';

}
function get_baseURL(){
    return $baseurl =  $_SERVER['HTTP_REFERER'];
}
function get_fullPATH(){

}



?>

