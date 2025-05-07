$(document).ready(function() {
    //enviar dados para a database
    $('#contact_form').submit( function(e) {
        e.preventDefault()
        let name = $('#name').val()
        let email = $('#email').val()
        let phone = $('#phone').val()
        let description = $('#description').val()
        let message = ''

        if(description.length < 10) {
            message = 'Por favor, descreva o seu problema com mais detalhes.'
        }

        const phoneRegex = /^(\+|00)?\d{1,3}[\s\-]?(\(?\d{2,4}\)?[\s\-]?)?\d{3,5}[\s\-]?\d{3,5}$/
        if(!phoneRegex.test(phone)) {
            message = 'Por favor, coloque um número de telefone válido.'
        }
        
        if(name.length < 3) {
            message = 'Por favor, preencha o seu nome completo.'
        }

        if(!email) {
            message = 'Por favor, preencha o seu email.'
        }

        if(message) {
            $('#error_contact').html(message)
            return
        }
        
        let formData = new FormData(this)
        $.ajax({
            url: '../php/processing/contacts.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                    $('#alert_div_contact').css('display', 'flex')

                    setTimeout(() => {
                        $('#alert_div_contact').css('display', 'none')
                        $('#email,#name,#phone,#description').val('')
                    }, 6000)
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error: ' + error)
                console.log('ERRO XHR:' + xhr)
                console.log('ERRO STATUS:' + status)
                console.log('Erro na conexão com o back-end: ' + response)
            }
        })
    })
})