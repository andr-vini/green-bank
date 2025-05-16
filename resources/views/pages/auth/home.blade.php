@extends('layouts.app')

@section('body')
<div class="border-l border-gray-200 w-full h-dvh">
    @include('includes.header-main')
    <main class="bg-slate-100 px-10 py-7 h-full">
        <div class="flex gap-5">
            <button type="button" class="btn-default  py-3 cursor-pointer w-full" id="showModalDeposit">Depositar</button>
            <button type="button" class="btn-default  py-3 cursor-pointer w-full">Transferir</button>
        </div>
    </main>
</div>

@include('includes.modals.modal-make-deposit')
@endsection

@section('scripts')
    <script src="/js/scripts/modal.js"></script>
    <script src="/js/scripts/imask-money.js"></script>
    <script src="/js/pages/home.js"></script>
@endsection