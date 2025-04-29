$(document).ready(function() {
    $('#search_index').submit(function(e) {
        e.preventDefault()
        let search = $('#search').val()
        console.log(search)
        if(search !== '') {
            $('#h3_search').text('Eventos Pesquisados: ' + search)
        } else {
            $('#h3_search').text('')
        }
        
        formData = new FormData(this)
        $.ajax({
            url: '../php/processing/process_index.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#search_div').html(response)
            },
            error: function(xhr, status, error) {
                console.log('ERROR: ' + error)
                console.log('STATUS: ' + status)
                console.log('XHR: ' + xhr)
                console.log('Erro ao enviar formulário')
                $('#search_div').html('<p>Erro na busca de eventos.</p>')
            }
        })
    })
})