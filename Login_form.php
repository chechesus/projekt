<!DOCTYPE html>
<html lang="sk">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Prihlásiť sa</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php include 'api/session.php';?> 

<style>
    @supports (-webkit-appearance: none) or (-moz-appearance: none) {
      .checkbox-wrapper-14 input[type=checkbox] {
        --active: #275EFE;
        --active-inner: #fff;
        --focus: 2px rgba(39, 94, 254, .3);
        --border: #BBC1E1;
        --border-hover: #275EFE;
        --background: #fff;
        --disabled: #F6F8FF;
        --disabled-inner: #E1E6F9;
        -webkit-appearance: none;
        -moz-appearance: none;
        height: 21px;
        outline: none;
        display: inline-block;
        vertical-align: top;
        position: relative;
        margin: 0;
        cursor: pointer;
        border: 1px solid var(--bc, var(--border));
        background: var(--b, var(--background));
        transition: background 0.3s, border-color 0.3s, box-shadow 0.2s;
      }
      .checkbox-wrapper-14 input[type=checkbox]:after {
        content: "";
        display: block;
        left: 0;
        top: 0;
        position: absolute;
        transition: transform var(--d-t, 0.3s) var(--d-t-e, ease), opacity var(--d-o, 0.2s);
      }
      .checkbox-wrapper-14 input[type=checkbox]:checked {
        --b: var(--active);
        --bc: var(--active);
        --d-o: .3s;
        --d-t: .6s;
        --d-t-e: cubic-bezier(.2, .85, .32, 1.2);
      }
      .checkbox-wrapper-14 input[type=checkbox]:disabled {
        --b: var(--disabled);
        cursor: not-allowed;
        opacity: 0.9;
      }
      .checkbox-wrapper-14 input[type=checkbox]:disabled:checked {
        --b: var(--disabled-inner);
        --bc: var(--border);
      }
      .checkbox-wrapper-14 input[type=checkbox]:disabled + label {
        cursor: not-allowed;
      }
      .checkbox-wrapper-14 input[type=checkbox]:hover:not(:checked):not(:disabled) {
        --bc: var(--border-hover);
      }
      .checkbox-wrapper-14 input[type=checkbox]:focus {
        box-shadow: 0 0 0 var(--focus);
      }
      .checkbox-wrapper-14 input[type=checkbox]:not(.switch) {
        width: 21px;
      }
      .checkbox-wrapper-14 input[type=checkbox]:not(.switch):after {
        opacity: var(--o, 0);
      }
      .checkbox-wrapper-14 input[type=checkbox]:not(.switch):checked {
        --o: 1;
      }
      .checkbox-wrapper-14 input[type=checkbox] + label {
        display: inline-block;
        vertical-align: middle;
        cursor: pointer;
        margin-left: 4px;
      }
  
      .checkbox-wrapper-14 input[type=checkbox]:not(.switch) {
        border-radius: 7px;
      }
      .checkbox-wrapper-14 input[type=checkbox]:not(.switch):after {
        width: 5px;
        height: 9px;
        border: 2px solid var(--active-inner);
        border-top: 0;
        border-left: 0;
        left: 7px;
        top: 4px;
        transform: rotate(var(--r, 20deg));
      }
      .checkbox-wrapper-14 input[type=checkbox]:not(.switch):checked {
        --r: 43deg;
      }
      .checkbox-wrapper-14 input[type=checkbox].switch {
        width: 38px;
        border-radius: 11px;
      }
      .checkbox-wrapper-14 input[type=checkbox].switch:after {
        left: 2px;
        top: 2px;
        border-radius: 50%;
        width: 17px;
        height: 17px;
        background: var(--ab, var(--border));
        transform: translateX(var(--x, 0));
      }
      .checkbox-wrapper-14 input[type=checkbox].switch:checked {
        --ab: var(--active-inner);
        --x: 17px;
      }
      .checkbox-wrapper-14 input[type=checkbox].switch:disabled:not(:checked):after {
        opacity: 0.6;
      }
    }
  
    .checkbox-wrapper-14 * {
      box-sizing: inherit;
    }
    .checkbox-wrapper-14 *:before,
    .checkbox-wrapper-14 *:after {
      box-sizing: inherit;
    }
  </style>
<body>
    <div class="grid-container">  
    <?php include 'website_elements/menu.php';?> 
    </div>
    
    <div class="login-box">
      <h2>Prihlasenie</h2>
      <form id="login-form" action="api/login.php" method="post">
          <input type="text" name="identifier" placeholder="Meno / mail " required autocomplete="on">
          <input type="password" name="password" placeholder="Heslo" required autocomplete="on">
          <div class="checkbox-wrapper-14">
              <input id="s1-14" type="checkbox" name="remember_me" value="1">
              <label for="s1-14">Zapamätať Prihlásenie</label>
          </div>
           <input type="submit" value="Prihlásiť sa">
            <?php // Add CSRF token
            $csrf_token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $csrf_token;
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        </form>
        <div class="register-link">
            <a href="reg_form.php" id="show-register">Registrovať nový účet</a>
        </div>
    </div>

    <?php include 'website_elements/footer.php';?>

<script>
const checkbox = document.getElementById('s1-14');
  checkbox.addEventListener('change', () => {
    if (checkbox.checked) {
      // local uloženie v prehliadači
      localStorage.setItem('identifier', document.getElementsByName('identifier')[0].value);
      localStorage.setItem('password', document.getElementsByName('password')[0].value);
      } else {
      // Remove login credentials from local storage
      localStorage.removeItem('identifier');
      localStorage.removeItem('password');
      }
    });
</script>

</body>
</html>