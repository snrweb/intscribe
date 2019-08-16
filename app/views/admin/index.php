
<?php $this->setSiteTitle('Admin Area') ?>

    <?php $this->start('body') ?>
      <section>
        <table class="a-table">
          <thead>
            <td>Store Name</td>
            <td>Category</td>
            <td>Email</td>
            <td>Phone</td>
            <td>Location</td>
            <td>Rating</td>
            <td>About</td>
            <td>Visibility</td>
            <td>Delete</td>
            <td>Send Email</td>
          </thead>

          <?php foreach($this->stores as $s): ?>
            <tbody>
              <td><?= $s->store_name ?></td>
              <td><?= $s->store_category ?></td>
              <td><?= $s->store_email ?></td>
              <td><?= $s->store_phone ?></td>
              <td><?= $s->store_street.', ' ?><?= $s->store_city.', ' ?><?= $s->store_country ?></td>
              <td><?= $s->store_rating ?></td>
              <td><?= $s->store_about ?></td>
              <td>
                <button 
                  class="<?php 
                            if($s->deleted == 0) { 
                              echo 'btn a-tableVisibiltyOn'; } else {
                              echo 'btn a-tableVisibiltyOff';
                              } 
                          ?>" >
                  <?php 
                    if($s->deleted == 0) { 
                      echo 'On'; } 
                    else {
                      echo 'Off';
                    } 
                  ?> 
                </button>
              </td>
              

              <td><button class="btn a-tableDelete">Delete</button></td>
              <td><button class="btn a-tableSendMsg">
                <?php echo file_get_contents(ROOT.'/public/images/svg/small123.svg') ?></button></td>
            </tbody>
          <?php endforeach ?>

        </table>
      </section>

        
    <?php $this->end() ?>