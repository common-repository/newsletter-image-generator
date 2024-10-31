<div id="image_generator" class="panel woocommerce_options_panel">

    <p><?php _e('Choose from the options below for the image to generate an image to be used for the newsletter') ?></p>

    <div class="inside">
        <!-- the image -->
        <p class="hide-if-no-js">
            <img src="<?php echo $imageUrl ?>" width="400"
                 id="image-generatored"
                 class="attachment-266x266 size-266x266" alt=""
                 sizes="(max-width: 266px) 100vw, 266px">
        </p>

        <hr>

        <div class="generator-inputs" id="accordation">

            <?php
            $form = \Oaattia\WoocommerceGenerator\FormsBuilder\Fields::create();

            foreach ($form->getFields() as $field) {
                $form = new Oaattia\WoocommerceGenerator\FormsBuilder\Form($field);
                echo $form->create();
            }
            ?>

        </div>

        <div class="generator-inputs">
            <p class="form-field _field">
                <label for="render">Render</label>
                <input style="margin-left: 0;" type="button" class="button" id="image-generator-sumit" value="Render">
            </p>
        </div>
        <?php
    ?>
</div>