$(document).ready(function() {
    $('#form_register').on('submit', function(event) {
        event.preventDefault()

        let name = $('#name').val()
        let username = $('#username').val()
        let email = $('#email').val()
        let password = $('#password').val()
        let confirmPassword = $('#confirmPassword').val()
        let city = $('#city').val()
        let country = $('#country').val()
        let phone = $('#phone').val()
        let eventType = $('#event_type').val()
        let image = $('#user_img').val()
        let message = ''

        //validação do formulário
        if(name.length < 4) {
            message = 'Por favor, adicione seu nome completo.'
            alert(message)
            return
        }

        if(username.length < 3) {
            message = 'Você precisa adicionar letras no username.'
            alert(message)
            return
        }

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if(!emailRegex.test(email)) {
            errorMessage = 'Email inválido!'
            alert(message)
            return
        }

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/;
        if(!passwordRegex.test(password)) {
            message = 'A senha deve ter no mínimo 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial.'
            alert(message)
            return
        }

        if(password !== confirmPassword) {
            message = 'As senhas não conferem!'
            alert(message)
            return
        }

        if(city.length < 3) {
            message = 'Por favor, adicione sua cidade.'
            alert(message)
            return
        }
        
        if(country.length < 2) {
            message = 'Por favor, adicione seu país.'
            alert(message)
            return
        }

        const phoneRegex = /^(\+|00)?\d{1,3}[\s\-]?(\(?\d{2,4}\)?[\s\-]?)?\d{3,5}[\s\-]?\d{3,5}$/
        if(!phoneRegex.test(phone)) {
            message = 'Número de telefone inválido!'
            alert(message)
            return
        }

        if(!eventType) {
            message = 'Por favor, selecione o tipo de evento.'
            alert(message)
            return
        }

        if(!image) {
            message = 'Por favor, escolha uma imagem.'
            alert(message)
            return
        }

        if(message) {
            $('#message_error').text(message)
            return;
        }
        console.log('Form submitted')

        //enviar via ajax para process_register.php
        var formData = new FormData(this)

        $.ajax({
            url: '../php/processing/process_register.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                response = JSON.parse(response)

                if(response.success) {
                    window.location.href = '../php/index.php'
                } else {
                    $('#message_error').text(response.message)
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error: ' + error)
                console.log('ERRO XHR:' + xhr)
                console.log('ERRO STATUS:' + status)
                $('#message_error').text('Ocorreu um erro ao processar o registro.')
            }
        })
    })
})