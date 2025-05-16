function setMoneyMask(selector){
    const moneyInput = document.querySelectorAll(selector);
    const maskOptions = {
        mask: 'R$ num',
        blocks: {
            num: {
                mask: Number,
                thousandsSeparator: '.',
                radix: ',',
                scale: 2,
                signed: false,
                padFractionalZeros: true,
                normalizeZeros: true,
            }
        }
    };
    
    let masks = [];
    moneyInput.forEach(element => {
        masks = [...masks, IMask(element, maskOptions)];
    });

    return masks;
}