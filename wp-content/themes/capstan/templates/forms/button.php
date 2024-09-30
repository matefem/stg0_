<?php

    $p = getParams(array("title" => "Accepter", "classes" => "", "a-classes" => "",
        "icon" => "" , "disabled" => false,
        "url" => "",
        "title" => "",
        "target" => "_self",
        "attr" => ""));
?>

<button name="form-button" data-disabled="<?php echo $p["disabled"]; ?>" class="<?php echo $p["classes"]; ?>" <?php echo $p["attr"]; ?>

    <?php if (!empty($p["url"])) { echo 'data-follow-link';} ?>
    >
    <?php if (!empty($p["url"])) {?>
        <a href="<?php echo $p["url"]; ?>" target="<?php echo $p["target"]; ?>" class="<?php echo $p["a-classes"]; ?>">
            <?php echo t($p["title"]); ?>
        </a>
    <?php } else { ?>
        <?php echo t($p["title"]); ?>
    <?php } ?>

</button>