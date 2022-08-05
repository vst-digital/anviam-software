<?php

function makeImageFromName($name) {
    $userImage = "";
    $shortName = "";

    $names = explode(" ", $name);

    foreach ($names as $w) {
        if(isset($w[0])){
            $shortName .= $w[0];
        }
    }

    $userImage = '<div class="name-image bg-primary">'.$shortName.'</div>';
    return $userImage;
}
