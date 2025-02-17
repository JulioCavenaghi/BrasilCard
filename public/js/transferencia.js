document.getElementById('numero_conta_destino').addEventListener('input', function(event) {
    let value = event.target.value.replace(/\D/g, '');
    
    if (value.length > 11) {
        value = value.substring(0, 11);
    }
    
    if (value.length > 10) {
        value = value.substring(0, 10) + '-' + value.substring(10, 12);
    }

    event.target.value = value;
});
