function fetchNotifications() {
    fetch('get_notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(notification => {
                    alert(notification.message); // Zobrazenie obsahu notifikácie v alert() okne
                });
            }
        })
        .catch(error => console.error('Chyba pri načítaní notifikácií:', error));
}

// Spusti kontrolu každých 5 sekúnd
setInterval(fetchNotifications, 5000);
