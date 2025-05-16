function toggleModal(selector) {
    var modal = document.querySelector(selector);

    if (modal.classList.contains('hidden')) {
        //Open
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100', 'translate-y-0');
            modal.classList.remove('opacity-0', 'translate-y-10');
        }, 10);
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        return;
    } 
    //Close
    modal.classList.remove('opacity-100', 'translate-y-0');
    modal.classList.add('opacity-0', 'translate-y-10');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300); // Tempo da transição
    
    const inputs = modal.querySelectorAll('input[type="text"], input[type="number"], input[type="email"], input[type="date"], input[type="tel"], textarea');
    inputs.forEach(input => {
        input.value = '';
    });

    const selects = modal.querySelectorAll('select');
    selects.forEach(select => {
        select.value = '';
    });
    
    const imgPreview = document.getElementById('img-preview');
    if (imgPreview) {
        imgPreview.src = '/img/blank.jpg'
    }
    
    document.body.classList.remove('overflow-hidden');
}
