$(document).ready(function() {
    //enviar dados para a database
    $('#contact_form').submit( function(e) {
        e.preventDefault()
        
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