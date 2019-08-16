<?php
    use Core\Session;
?>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="<?=PROOT?>public/css/gen.css" />
    <title> <?=$this->getSiteTitle(); ?> </title>
  </head>

  <body>
      <?= $this->component('Navbar') ?>
      <div id="intscribe">
        <?=$this->content('body') ?>
      </div>
  </body>

</html>
