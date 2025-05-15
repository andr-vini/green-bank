//Input de senhas
document.querySelector('.eye-icon-container').addEventListener('click', function(e){
    let eyeOpened = e.currentTarget.querySelector('.opened-eye');
    let eyeClosed = e.currentTarget.querySelector('.closed-eye');


    if(!eyeOpened.classList.contains('hidden')){
        eyeOpened.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
        toggleVisibilityPassword(e.currentTarget.previousElementSibling, 'text')
        return;
    }

    eyeClosed.classList.add('hidden');
    eyeOpened.classList.remove('hidden');
    toggleVisibilityPassword(e.currentTarget.previousElementSibling, 'password')
});

function toggleVisibilityPassword(input, type){
    input.setAttribute('type', type);
}