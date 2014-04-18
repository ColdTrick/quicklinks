<?php
/**
 * some settings for the QuickLinks widget
 */

$widget = elgg_extract("entity", $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

echo "<div>";
echo "<label for='quicklinks-wdiget-num-display-" . $widget->getGUID() . "'>";
echo elgg_echo("widget:numbertodisplay");
echo "</label>";
echo elgg_view("input/select", array("name" => "params[num_display]", "value" => $num_display, "options" => range(1,10), "class" => "mls"));
echo "</div>";
