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

    document.querySelector('#save-transfer').addEventListener('click', function(event){
        let inputAccount = document.querySelector('#account');
        let account = inputAccount.value;

        let input = masks[1].el.input;
        let numericValue = masks[1].unmaskedValue;

        if (!numericValue || parseFloat(numericValue.replace(',', '.')) <= 0) {
            toastr.error('Por favor, insira um valor válido maior que zero');
            return;
        }

        makeTransfer(account, input.value);
    });

    document.querySelector('#showModalTransfer').addEventListener('click', function(event){
        toggleModal('#make-transfer')
    })

    loadHistoricTransaction();
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
        loadHistoricTransaction();
        // console.log(data); // Pode usar pra depuração

    } catch (error) {
        toastr.error(error.message);
        console.error('Erro no depósito:', error);
    }
}

async function makeTransfer(account, amount) {
    try {
        const response = await fetch('/transfer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                'account': account,
                'amount': amount
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Erro ao processar o depósito');
        }

        toastr.success(data.message);
        toggleModal('#make-transfer');
        loadHistoricTransaction();
        // // console.log(data); // Pode usar pra depuração

    } catch (error) {
        toastr.error(error.message);
        // console.error('Erro no depósito:', error);
    }
}

async function loadHistoricTransaction() {
    try {
        const response = await fetch('/load-historic', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Erro ao carregar histórico');
        }

        document.querySelector('#body-historic-transactions').innerHTML = '';
        data.historicTransactions.map((transaction, index) => {
            let bg = '';
            if(index % 2 == 0){
                bg = 'bg-gray-200';
            }else{
                bg = 'bg-gray-100';
            }

            let btn = `<button class="revert btn-default px-3 py-1 cursor-pointer hover:bg-green-500" data-id="${transaction.id}">Reverter</button>`
            if(transaction.status == 'Revertido'){
                btn = '-';
            }
            document.querySelector('#body-historic-transactions').innerHTML += `
                <div class="[&>*]:min-w-30 min-h-8 py-3 ${bg} flex items-center justify-center gap-5">
                    <div>${transaction.type_transaction}</div>
                    <div>${transaction.balance}</div>
                    <div>${transaction.beneficiary_user != null ? transaction.beneficiary_user.account.number : '-'}</div>
                    <div>${transaction.created_at}</div>
                    <div>${transaction.status}</div>
                    <div>${btn}</div>
                </div>
            `
        });

        document.querySelectorAll('button.revert').forEach(element => {
            element.addEventListener('click', function(event){
                revertTransaction(event.target.dataset.id)
            });
        });
    } catch (error) {
        toastr.error(error.message);
    }
}

async function revertTransaction(id) {
    try {
        const response = await fetch('/revert-transaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Erro ao carregar histórico');
        }

        loadHistoricTransaction();
    } catch (error) {
        toastr.error(error.message);
    }
}