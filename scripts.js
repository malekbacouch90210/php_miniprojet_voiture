
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});


const buttons = document.querySelectorAll('.btn');
buttons.forEach(button => {
    button.addEventListener('mouseover', () => {
        button.style.boxShadow = '0px 10px 20px rgba(0, 0, 0, 0.2)';
        button.style.transform = 'translateY(-5px)';
    });

    button.addEventListener('mouseout', () => {
        button.style.boxShadow = '';
        button.style.transform = '';
    });
});


document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', event => {
        const inputs = form.querySelectorAll('input:required');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = 'red';
                input.style.boxShadow = '0px 0px 10px rgba(255, 0, 0, 0.5)';
            } else {
                input.style.borderColor = '#4A90E2';
                input.style.boxShadow = '0px 0px 10px rgba(74, 144, 226, 0.5)';
            }
        });

        if (!isValid) {
            event.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
        }
    });
});
