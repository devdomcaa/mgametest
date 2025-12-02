/**
 * Hlavní JavaScript soubor pro MGame web
 */

// Automatické skrytí alertů po 5 sekundách
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
});

// Potvrzovací dialog pro mazání
function confirmDelete(message) {
    return confirm(message || 'Opravdu chcete tuto položku smazat?');
}

// Jednoduché zvýraznění aktivního menu položky
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('.nav-menu a');
    
    menuLinks.forEach(function(link) {
        if (link.getAttribute('href') === currentPath) {
            link.style.color = 'var(--purple-primary)';
        }
    });
});