<?php

function getButtons() {
    return array(
        "add.png" => _("Add"),
        "cancel.png" => _("Cancel"),
        "next.png" => _("Next"),
        "back.png" => _("Back"),
        "addcomment.png" => _("Add Comment"),
        "subscribe.png" => _("Subscribe"),
        "unsubscribe.png" => _("Unsubscribe"),
        "reject.png" => _("Reject"),
        "updatePassword.png" => _("Update Password"),
        "delete.png" => _("Delete"),
        "select.png" => _("Select"),
        "update.png" => _("Update"),
        "assign.png" => _("Assign"),
        "search.png" => _("Search"),
        "edit.png" => _("Edit"),
        "approve.png" => _("Approve"),
        "done.png" => _("Done"),
        "publish.png" => _("Publish"),
        "move.png" => _("Move"),
        "remove.png" => _("Remove"),
        "restore.png" => _("Restore"),
        "submit.png" => _("Submit"),
        "expunge.png" => _("Expunge"),
        "restorehere.png" => _("Restore Here"),
        "reply.png" => _("Reply"),
        "new.png" => _("New"),
        "reset.png" => _("Reset"),
        "archive.png" => _("Archive"),
        "browse.png" => _("Browse"),
        "checkin.png" => _("Check In"),
        "checkout.png" => _("Check Out"),
        "download.png" => _("Download"),
        "email.png" => _("Email"),
        "movehere.png" => _("Move Here"),
        "begin.png" => _("Begin"),
        "login.png" => _("Login"),
        "checkall.png" => _("Check All"),
        "clearall.png" => _("Clear All"),
    );
}

function generate_button($fn, $text, $height) {
    global $font, $fontsize;
    $text = strtoupper($text);
    $size = imagettfbbox($fontsize,0, $font,$text);
    $dx = abs($size[2]-$size[0]);
    #$dy = abs($size[5]-$size[3]);
    $dy = $height;
    $xpad=9;
    $ypad=9;
    $im = ImageCreateTrueColor($dx+$xpad,$dy+$ypad);
    $white = ImageColorAllocate($im, 255,255,255);
    $black = ImageColorAllocate($im, 0,0,0);
    imagefill($im, 0, 0, $white);
    //ImageRectangle($im, 0, 0, $dx + $xpad - 1, $dy + $ypad - 1, $black);
    ImageRectangle($im, 2, 2, $dx + $xpad - 3, $dy + $ypad - 3, $black);
    ImageTTFText($im, $fontsize, 0, (int)($xpad/2), $dy+(int)($ypad/2), $black, $font, $text);
    ImagePng($im, $fn);
    ImageDestroy($im);
}

function get_height($buttons) {
    global $font, $fontsize;
    $maxHeight = 0;

    foreach ($buttons as $fn => $text) {
        $text = strtoupper($text);
        $size = imagettfbbox($fontsize,0, $font,$text);
        $dy = abs($size[5]-$size[3]);
        if ($dy > $maxHeight) {
            $maxHeight = $dy;
        }
    }
    return $maxHeight;
}

$argv = $_SERVER['argv'];
$sLocale = $argv[1];

error_reporting(E_ALL);
putenv('LANG=' . $sLocale);
setlocale(LC_ALL, $sLocale);
// Set the text domain
$sDomain = 'knowledgeTree';
$btd = bindtextdomain($sDomain, "/home/nbm/cvs/knowledgeTree/i18n");
textdomain($sDomain);

$font = "/usr/share/fonts/bitstream-vera/Vera.ttf";
$fontsize = 8;

$buttons = getButtons();
$path = "/tmp/";

$height = get_height($buttons);

foreach ($buttons as $fn => $text) {
    generate_button($path . $fn, $text, $height);
}

?>
