$(document).ready(function() {
    $('#login_form').submit(function(event) {
        event.preventDefault();
        
        let email = $('#email').val()
        let password = $('#password').val()
        let message = ''

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if(!emailRegex.test(email)) {
            message = 'Email inválido'
        }

        if(password.length < 7) {
            message = 'Esqueceu a senha?'
            $('#message_error').prepend(message)
            $('#alterar_senha').css('display', 'inline')
            return
        }

        if(message) {
            $('#message_error').text(message)
            return
        }

        var formData = new FormData(this)
        $.ajax({
            url: '../php/processing/process_login.php',
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
                console.log('ERRO error: ' + error)
                console.log('ERRO status: ' + status)
                console.log('ERRO xhr: ' + xhr)
                $('#message_error').text('Erro ao processar o login. Tente novamente.')
            }
        })
    })
})