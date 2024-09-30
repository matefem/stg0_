<?php
    $p = getParams(array("name" => uniqid(), "placeholder" => "",
        "label" => "",
        "classes" => "", "type" => "text", "icon" => "",
        "input-classes" => "", "for" => uniqid(),
        "button" => "",
        "button-attr" => "",
        "value" => "",
        "disabled" => false,
        "required" => false,
        "autocomplete" => "false",
        "error" => ""));

    if ($p["type"] == "password" && empty($p["button"])) {
        $p["icon"] = "icon-eye";
    }

    if (!empty($p["button"])) {
        $p["value"] = "**********";
        $p["disabled"] = true;
    }

    if ($p["required"]) {$p["classes"] .= " required";}
?>

<div name="form-text" class="form-item <?php echo $p["classes"]; ?>" data-type="<?php echo $p["type"]; ?>"
     data-disabled="<?php echo $p["disabled"]; ?>" data-component="Text">

    <?php if (!empty($p["label"])) { ?>
        <label for="<?php echo $p["for"]; ?>" class=""><?php echo _e($p["label"], "capstan"); ?></label>
    <?php } ?>

    <input id="<?php echo $p["for"]; ?>" class="<?php $p["input-classes"]; ?>"
        name="<?php echo $p["name"]; ?>" type="<?php echo $p["type"]; ?>"  <?php if ($p["required"]) echo 'required'; ?>
        placeholder="<?php _e($p["placeholder"], 'capstan'); ?>" value="<?php echo $p["value"];?>" autocomplete="<?php echo $p["autocomplete"]; ?>">

    <?php if (!empty($p["icon"])) { ?>
        <i class="icon <?php echo $p["icon"]; ?>"></i>
    <?php } ?>

    <?php if (!empty($p["button"])) { ?>
        <button <?php echo $p["button-attr"]; ?>><?php echo $p["button"]; ?></button>
    <?php } ?>

    <?php if (!empty($p["error"])) { ?>
        <div class="error-message hidden"><?php echo _e($p["error"], "capstan"); ?></div>
    <?php } ?>
</div>