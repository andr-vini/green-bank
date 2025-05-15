function setCPFMask(selector){
    const cpfInput = document.querySelectorAll(selector);
    cpfInput.forEach(element => {
        IMask(element, {
            mask: '000.000.000-00',
            prepare: (str) => str.replace(/\D/g, ''), // só números
        });
    });
}