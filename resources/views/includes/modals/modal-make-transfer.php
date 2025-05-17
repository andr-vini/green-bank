<div class="modal hidden" id="make-transfer">
    <div class="modal-content">
        <div>
            <!-- Cabeçalho do Modal -->
            <div class="modal-header">
                <div class="text-lg font-semibold" id="title-event">Faça sua transferência agora!!</div>
                <span class="cursor-pointer p-1 rounded-md hover:bg-slate-100" onclick="toggleModal('#make-transfer')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            </div>

            <!-- Corpo do Modal -->
            <div class="modal-body mt-4">
                <div>
                    <label for="account">Digite o número da conta destinatária</label>
                    <input id="account" type="text" name="account" class="input-default-max mt-2"/>
                </div>
                <div class="mt-4">
                    <label for="amount">Quanto você quer depositar?</label>
                    <input id="amount" type="text" name="amount" class="input-default-max mt-2 input-money" placeholder="R$"/>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-default py-3 cursor-pointer w-full" id="save-transfer">Transferir</button>
        </div>
    </div>
</div>