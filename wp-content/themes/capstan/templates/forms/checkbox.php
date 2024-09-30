<?php
    $p = getParams(array("name" => uniqid(), "for" => uniqid(),
        "text" => "Sample", "classes" => "mt-25",
        "required" => false,
        "color" => "text-dark"));

    if ($p["required"]) {$p["classes"] .= " required";}
?>

<div name="form-checkbox" class="form-item <?php echo $p["classes"]; ?>" data-component="Checkbox">
    <input class="<?php echo str_replace('text', 'border', $p["color"]); ?>" id="<?php echo $p["for"]; ?>" type="checkbox" name="<?php echo $p["name"]; ?>" <?php if ($p["required"]) echo 'required'; ?>/>
    <label for="<?php echo $p["for"]; ?>" class="paragraph-dark-small <?php echo $p["color"]; ?>"><?php echo $p["text"]; ?></label>
</div>