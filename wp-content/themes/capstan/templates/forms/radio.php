<?php
    $p = getParams(array("name" => uniqid(),
    "classes" => "mt-25",
    "value" => "",
    "required" => false,
    "values" => array(),
    "color" => "text-gray"));

    if ($p["required"]) {$p["classes"] .= " required";}
?>

<div name="form-radio" class="form-item <?php echo $p["classes"]; ?>" data-component="Radio">
    <?php foreach($p["values"] as $k => $v) { $for = uniqid();?>
        <div class="mt-15">
            <input class="<?php echo str_replace('text', 'border', $p["color"]); ?>" id="<?php echo $for; ?>" <?php if ($p["required"]) echo 'required'; ?> type="radio" name="<?php echo $p["name"]; ?>" value="<?php echo $k; ?>" <?php if ($k == $p["value"]) {echo 'checked';} ?>/>
            <label for="<?php echo $for; ?>" class="paragraph-dark-small <?php echo $p["color"]; ?>"><?php echo $v; ?></label>
        </div>
    <?php } ?>
</div>