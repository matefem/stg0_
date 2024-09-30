<?php
    $p = getParams(array("name" => uniqid(), "value" => ""));
?>

<input type="hidden" name="<?php echo $p["name"];?>" value="<?php echo $p["value"]; ?>" />