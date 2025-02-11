export function setupPasswordStrengthChecker() {
    try {
        var code = document.getElementById("password");
        var strengthbar = document.getElementById("meter");

        if (!code || !strengthbar) {
            console.error("Chýba element s ID 'password' alebo 'meter'.");
            return;
        }

        code.addEventListener("input", function () {
            strengthbar.value = calculateStrength(code.value);
        });

        function calculateStrength(password) {
            var strength = 0;

            if (password.length > 5) {
                if (password.match(/[a-z]/)) strength += 1;
                if (password.match(/[A-Z]/)) strength += 1;
                if (password.match(/[0-9]/)) strength += 1;
                if (password.match(/[$@#&!]/)) strength += 1;
                if (password.length > 12) strength += 1;
            }

            return (strength / 5) * 100; //vratenie percent 0-100

            
        }
    
        function updateStrengthInfo(password) {
            var info = [];
            if (password.length > 5) info.push("Minimálne 5 znakov");
            if (password.match(/[a-z]/)) info.push("Malé písmená");
            if (password.match(/[A-Z]/)) info.push("Veľké písmená");
            if (password.match(/[0-9]/)) info.push("Číslice");
            if (password.match(/[$@#&!]/)) info.push("Špeciálne znaky");
            if (password.length > 12) info.push("Viac ako 12 znakov");

            // Zobrazenie informácií o sile hesla
            strengthInfo.innerHTML = "Požiadavky: " + (info.length > 0 ? info.join(", ") : "Žiadne splnené");
        } 
    
    } catch (error) {
        
    }
    
}

function togglePasswordVisibility(passwordFieldId, toggleIconId) {
    const passwordField = document.getElementById(passwordFieldId);
    const toggleIcon = document.getElementById(toggleIconId);
    
    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;

    // Toggle the eye icon
    toggleIcon.classList.toggle('fa-eye');
    toggleIcon.classList.toggle('fa-eye-slash');
}

export function setupPasswordToggleListeners() {
    const passwordToggle = document.getElementById('togglePassword');
    const passwordCheckToggle = document.getElementById('togglePasswordCheck');

    passwordToggle.addEventListener('click', function() {
        togglePasswordVisibility('password', 'togglePassword');
    });

    passwordCheckToggle.addEventListener('click', function() {
        togglePasswordVisibility('password_check', 'togglePasswordCheck');
    });
}

