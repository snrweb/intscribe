<?php
    use Core\CSRF;
?>
<?php $this->setSiteTitle('product Admin Area') ?>
    <?php $this->start('body') ?>
        <section class="a-productSubCategories-menu">
            <?php foreach($this->fcat as $c):?>
                <a href="<?=PROOT?>admin223/product/category/<?= $this->mcat ?>/<?= str_replace(' ', '-', $c) ?>" >
                    <?= $c ?>
                </a>
            <?php endforeach ?>
        </section>
        <section>
            <table class="a-table">

            <thead>
                <td>Product Name</td>
                <td>Category</td>
                <td>Price</td>
                <td>Details</td>
                <td>Rating</td>
                <td>Time Uploaded</td>
                <td>Visibility</td>
                <td>Delete</td>
            </thead>

            <?php foreach($this->products as $p): ?>
                <tbody>
                <td><?=$p->product_name ?></td>
                <td><?=$p->product_cat ?></td>
                <td><?=$p->product_price ?></td>
                <td><?=$p->product_details ?></td>
                <td><?=$p->product_rating ?></td>
                <td><?= timeFormatter($p->time_uploaded) ?></td>
                <td>
                    <button 
                    class="<?php 
                                if($p->deleted == 0) { 
                                echo 'btn a-tableVisibiltyOn'; } else {
                                echo 'btn a-tableVisibiltyOff';
                                } 
                            ?>" >
                    <?php 
                        if($p->deleted == 0) { 
                        echo 'On'; } 
                        else {
                        echo 'Off';
                        } 
                    ?> 
                    </button>
                </td>

                <td><button class="btn a-tableDelete">Delete</button></td>
                </tbody>
            <?php endforeach ?>

            </table>
        </section>

    <?php $this->end() ?>