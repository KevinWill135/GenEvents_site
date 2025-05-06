$(document).ready(function() {
    $('#username_form').submit(function(e) {
        e.preventDefault()
        
        let usermane = $('#username').val()

        $.get('../php/processing/forgot_password.php', { username: usermane })
            .done(function(response) {
                //console.log(response)
                response = JSON.parse(response)
                //console.log('Dados enviados para o back: ' + response.user_id)
                $('#get_username').css('display', 'none')
                $('#get_password').css('display', 'flex')

                let password_form = $('#password_form')
                $('<input>', {
                    type: 'hidden',
                    name: 'user_id',
                    value: response.user_id
                }).appendTo(password_form)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                alert('Erro ao enviar dados: ' + textStatus + ' - ' + errorThrown);
            })
    })
    
    $('#password_form').submit(function(e) {
        e.preventDefault()

        let password = $('#password').val()
        let confirmPassword = $('#confirmPassword').val()
        let message = ''

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/;
        if(!passwordRegex.test(password)) {
            message = 'A senha deve ter no mínimo 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial.'
            $('#message_forgot').html(message)
            return
        }

        if(confirmPassword != password || confirmPassword.lenght < 1) {
            message = 'As senhas precisam ser idênticas'
            $('#message_forgot').html(message)
            return
        }

        let formData = new FormData(this)
        $.ajax({
            url: '../php/processing/forgot_password.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = JSON.parse(response)
                if(response.success) {
                    alert('Dados alterados com sucesso: ' + response.message)
                    window.location.href = 'login.html'
                } else {
                    console.log('Erro ao alterar os dados: ' + response)
                }
            },
            error: function(xhr, error, status) {
                console.log('ERROR: ' + error)
                console.log('XHR: ' + XHR)
                console.log('STATUS: ' + status)
                console.log('Erro ao enviar para o php, LINE 48 JS')
            }
        })
    })
})