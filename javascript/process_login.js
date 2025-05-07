$(document).ready(function() {
    $('#login_form').submit(function(event) {
        event.preventDefault();
        
        let email = $('#email').val()
        let password = $('#password').val()
        let message = ''
        let forgot = ` <a id="alterar_senha" href="forgot_password.html" class="text-primary" style="display: none;">Alterar Senha</a>`

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if(!emailRegex.test(email)) {
            message = 'Email inválido'
        }

        if(password.length < 7) {
            message = 'Esqueceu a senha?'
        }

        if(message) {
            $('#message_error').html('')
            $('#message_error').append(forgot)
            $('#message_error').prepend(message)
            $('#alterar_senha').css('display', 'inline')
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
                console.log(response.message)
                response = JSON.parse(response)

                if(response.success) {
                    window.location.href = '../php/admin/admin.php'
                } else {
                    $('#message_error'). html('')
                    $('#message_error').append(forgot)
                    $('#message_error').prepend(response.message)
                    $('#alterar_senha').css('display', 'inline')
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