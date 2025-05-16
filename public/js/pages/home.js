document.addEventListener("DOMContentLoaded", function() {
    let masks = setMoneyMask('.input-money')

    document.querySelector('#showModalDeposit').addEventListener('click', function(){
        toggleModal('#make-deposit')
    })

    document.querySelector('#save-deposit').addEventListener('click', function(event){
        let input = masks[0].el.input;
        let numericValue = masks[0].unmaskedValue;

        if (!numericValue || parseFloat(numericValue.replace(',', '.')) <= 0) {
            toastr.error('Por favor, insira um valor válido maior que zero');
            return;
        }

        makeDeposit(input.value)
    })
})


async function makeDeposit(amount) {
    try {
        const response = await fetch('/deposit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ amount })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Erro ao processar o depósito');
        }

        toastr.success(data.message);
        toggleModal('#make-deposit');
        // console.log(data); // Pode usar pra depuração

    } catch (error) {
        toastr.error(error.message);
        console.error('Erro no depósito:', error);
    }
}