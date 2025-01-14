<?php
    $p = getParams(array("article" => array(), "classes" =>  ""));
    $post = $p["article"];

    $files = get_field("files", $post);
?>

<?php if ((!empty($files) && sizeof($files) > 0)) { ?>
    <div class="post-files">
        <div class="separator"></div>
        <div class="title">
            <i class="icon-attachment"></i>
            <span><?php if (@sizeof($files) <= 1) echo 'Fichier joint'; else echo 'Fichiers joints'; ?></span>
        </div>

        <ul>
            <?php foreach($files as $file) { if (empty($file["file"]["filesize"])) {continue;} ?>
                <li data-follow-link>
                    <a target="_blank" href="<?php echo $file["file"]["url"]; ?>">
                        <span><?php echo $file["file"]["title"]; ?></span> <span> - <?php echo round($file["file"]["filesize"] / 1024) . ' Ko'; ?></span>
                    </a>
                    <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><filter id="a"><feColorMatrix in="SourceGraphic" values="0 0 0 0 0.984394 0 0 0 0 0.000000 0 0 0 0 0.000000 0 0 0 1.000000 0"/></filter><path d="M14 3.5c.644 0 1.167.522 1.167 1.167v15.849l5.008-5.008a1.167 1.167 0 0 1 1.548-.09l.102.09a1.167 1.167 0 0 1 0 1.65l-7 7a1.19 1.19 0 0 1-.452.28 1.155 1.155 0 0 1-.252.056L14 24.5a1.18 1.18 0 0 1-.647-.195 1.245 1.245 0 0 1-.178-.147l.082.075a1.173 1.173 0 0 1-.066-.059l-.016-.016-7-7a1.167 1.167 0 1 1 1.65-1.65l5.008 5.009V4.667c0-.602.455-1.097 1.04-1.16Z" id="b"/></defs><g fill="none" fill-rule="evenodd"><circle stroke="#E62612" stroke-width="2" cx="20" cy="20" r="19"/><g transform="translate(3 3)" filter="url(#a)"><g transform="translate(3.5 3.5)"><mask id="c" fill="#fff"><use xlink:href="#b"/></mask><use fill="#000" fill-rule="nonzero" xlink:href="#b"/><g mask="url(#c)" fill="#000"><path d="M0 0h28v28H0z"/></g></g></g></g></svg>
                </li>
            <?php } ?>
        </ul>

    </div>
<?php } ?>