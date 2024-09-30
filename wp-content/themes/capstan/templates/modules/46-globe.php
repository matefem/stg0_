<div class="module module-46" data-component="Globe46Component" data-background="dark">
      <div class="content">
            <?php if(!empty(get_field('title'))) { ?>
                  <div class="title"><?php the_field('title'); ?></div>
            <?php } ?>
            <?php if(!empty(get_field('description'))) { ?>
                  <div class="description"><?php the_field('description'); ?></div>
            <?php } ?>
      </div>
      <div class="globe-container">
            <canvas></canvas>
            <div class="globe-callout-manager"></div>
      </div>
</div>