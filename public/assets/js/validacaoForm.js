//Validação de senha e confirmação de senha no formulário de registro

const formulario = document.getElementById('registerForm');
const password = document.getElementById('password');
const confirmPassword = document.getElementById('password_confirm');
const errorPassword = document.getElementById('messageErrorPassword');
const errorConfirmPassword = document.getElementById('messageErrorConfirmPassword');

// Validação de Usuário
const username = document.getElementById('username');
const errorUsername = document.getElementById('messageErrorUsername');


formulario.addEventListener('submit', function(event){
    // Validação de Senha
    errorPassword.textContent = "";
    errorConfirmPassword.textContent = "";
    if(password.value !== confirmPassword.value){
        event.preventDefault();
        errorConfirmPassword.textContent = "As senhas não coincidem. Por favor, tente novamente.";
        errorPassword.textContent = "";
    } else if (password.value.length < 6 || confirmPassword.value.length < 6) {
        event.preventDefault();
        errorPassword.textContent = "A senha deve ter no mínimo 6 caracteres.";
        errorConfirmPassword.textContent = "";
    }
    // Validação de Usuário

    errorUsername.textContent = "";
    if(username.value.trim() === ""){
        event.preventDefault();
        errorUsername.textContent = "O nome de usuário é obrigatório.";
    }else if(username.value.length < 3){
        event.preventDefault();
        errorUsername.textContent = "O nome de usuário deve ter no mínimo 3 caracteres.";
    }else if(username.value.length > 20){
        event.preventDefault();
        errorUsername.textContent = "O nome de usuário deve ter no máximo 20 caracteres.";
    }else if(!/^[a-zA-Z0-9_]+$/.test(username.value)){
        event.preventDefault();
        errorUsername.textContent = "O nome de usuário só pode conter letras, números e underscores.";
    }

}); 

