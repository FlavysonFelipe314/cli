import './bootstrap';

// Formatação de valores monetários
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para CPF
    const cpfInputs = document.querySelectorAll('input[name="cpf"]');
    cpfInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            }
        });
    });

    // Formatação de valores monetários nos inputs
    const amountInputs = document.querySelectorAll('input[name="amount"], input[name="balance"]');
    amountInputs.forEach(input => {
        input.addEventListener('blur', function(e) {
            if (e.target.value) {
                const value = parseFloat(e.target.value);
                if (!isNaN(value)) {
                    e.target.value = value.toFixed(2);
                }
            }
        });
    });
});
