<?php
include 'C:\xampp\htdocs\projekt\api\session.php';
require_once 'inc/header.php';
?>
<body>
  <div class="grid-container">  
    <?php require_once './website_elements/menu.php';?> 
  </div>
  
  <!-- Obsah stránky -->
  <div class="text" data-id="section-komentar">
    <h1>Komentár o stránke</h1>
    <br>
    <p>
      <?= var_dump($_SESSION)?>
      Vitajte na mojom fóre o vlakoch. Táto stránka je výsledkom mojej maturitnej práce z odboru informačné a sieťové technológie. Je určená pre všetkých nadšencov vlakovej dopravy, ktorí chcú diskutovať o novinkách, technológiách a iných zaujímavostiach v tejto oblasti.
    </p>
    <p>
      Na stránke nájdete interaktívne prvky, ako sú grafy zobrazujúce štatistiky prihlásených a registrovaných používateľov, ako aj informácie o aktuálnej záťaži systému (RAM a CPU). Súčasťou je aj diskusný chat, kde si môžete vzájomne pomáhať, zdieľať svoje názory a diskutovať o témach týkajúcich sa vlakovej dopravy.
    </p>
    <p>
      Pre podrobnejšie informácie o projekte, metodike práce, teoretických východiskách a výsledkoch, odporúčam preštudovať si priloženú wordovú dokumentáciu. Táto dokumentácia obsahuje kompletný opis zadania, ciele práce, použitú metodiku a dosiahnuté výsledky.
    </p>
  </div>
  
  <?php require_once './website_elements/content-grid.php';?> 

  <div class="text" data-id="section1">
    <h1>Toto je 1. paragraf</h1>
    <br>
    <p>
      Contrary to popular belief, Lorem Ipsum is not simply random text. 
      It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. 
      Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,
      consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.
      Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC.
      This book is a treatise on the theory of ethics, very popular during the Renaissance.
    </p>
  </div>

  <div class="text" data-id="section2">
    <h1>Toto je 2. paragraf</h1>
    <br>
    <p>
      Contrary to popular belief, Lorem Ipsum is not simply random text. 
      It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. 
      Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,
      consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.
      Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC.
    </p>
  </div>

  <div class="text" data-id="section3">
    <h1>Toto je 3. paragraf</h1>
    <br>
    <p>
      Contrary to popular belief, Lorem Ipsum is not simply random text. 
      It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. 
      Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,
      consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.
      Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC.
    </p>
  </div>

  <?php require_once './website_elements/footer.php';?>
</body>
</html>
