<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
    <?php if ($this->headline): ?>

    <<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
<?php if($this->message): ?>

    <p class="<?php echo $this->type; ?> message"><?php echo $this->message; ?></p>
<?php endif; ?>
<?php if(!empty($this->subcategories)): ?>
<div class="subcategory_list">
    <?php foreach( $this->subcategories as $subcat): ?>
    <div class="subcategory<?php echo ' '.$subcat['class']; ?>">
        <a href="{{link_url::<?php echo $subcat['id']; ?>}}" title="<?php echo ($subcat['content'] ? $subcat['content'] : $subcat['title']); ?><?php echo ($subcat['content'] ? $subcat['content'] : $subcat['title']); ?>"><?php echo ($subcat['content'] ? $subcat['content'] : $subcat['title']); ?> View All</a>
        <?php if(!empty($subcat['products'])): ?>
        <div class="product_list">

            <?php foreach ($subcat['products'] as $product): ?>
                <div<?php echo $product['cssID']; ?> class="<?php echo $product['class']; ?>">
                    <?php echo $product['html']; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php echo $this->pagination; ?>
<?php endif; ?>

</div>
