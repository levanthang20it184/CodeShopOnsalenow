<?php if (@$subCatList > 0 || @$brandList > 0) { ?>
    <div class="SearchCate">
        <span>Clear All</span>
        <button class="close-filter2"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
<?php } ?>
<?php if (!empty($subCatList)) { ?>

    <?php foreach ($subCatList as $key => $subValue) { ?>

        <div class="SearchCate" id="subcat_<?php echo $subValue->id; ?>">
            <span><?php echo $subValue->name; ?></span>
            <button class="close-filter1" onclick="getFilterClear('<?php echo $subValue->id; ?>','subcat');"><i
                        class="fa fa-times" aria-hidden="true"></i></button>
        </div>

    <?php }
} ?>
<?php if (!empty($brandList)) { ?>
    <?php foreach ($brandList as $key => $brandValue) { ?>

        <div class="SearchCate" id="brand_<?php echo $brandValue->id; ?>">
            <span><?php echo $brandValue->name; ?></span>
            <button class="close-filter1" onclick="getFilterClear('<?php echo $brandValue->id; ?>','brand');"><i
                        class="fa fa-times" aria-hidden="true"></i></button>
        </div>

    <?php }
} ?>