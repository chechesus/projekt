<?php 
require 'api/session.php';
require 'inc/header.php';
?> 
<body>
    <div class="grid-container">  
    <?php require_once 'website_elements/menu.php';?> 
    </div>
    <div class="album">
  <div class="responsive-container-block bg">
    <div class="responsive-container-block img-cont">
      <img class="img" src="images/train1.jpg">
      <img class="img" src="images/train3.jpg">
      <img class="img img-last" src="images/train4.jpg">
    </div>
    <div class="responsive-container-block img-cont">
      <img class="img img-big" src="images/train10.jpg">
      <img class="img img-big img-last" src="images/tain5.jpg">
    </div>
    <div class="responsive-container-block img-cont">
      <img class="img" src="images/train7.jpg">
      <img class="img" src="images/train8.jpg">
      <img class="img" src="images/train9.jpg">
    </div>
  </div>
</div>
    <?php require_once 'website_elements/footer.php';?>
</body>
</html>