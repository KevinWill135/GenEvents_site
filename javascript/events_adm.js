$(document).ready(function() {
    //Deletar evento
    $(document).on('click', '#delete_event_adm', function() {
        let eventId = $(this).data('id')

        let confirmar = confirm(`Tem certeza de que deseja deletar o evento de ID: ${eventId}?`)
        if(!confirmar) {
            alert('Operação cancelada!')
            return
        }

        $.post('../processing/delete_event.php', { event_id: eventId }, function(response) {
            console.log(response)
            response = JSON.parse(response);
            if (response.success) {
                // Remove the event from the list
                alert('Evento deletado com sucesso!');
                window.location.reload();
            } else {
                alert('Erro ao deletar o evento: ' + response.message);
            }
        })
    })

    //pegar evendo para editar
    $(document).on('click', '#slc_event_adm', function() {
        let eventId = $(this).data('id');
        $.post('../processing/get_event.php', { event_id: eventId }, function(html) {
            $('#div_edit_event').html(html);
            $('#edit_event').css('display', 'block')
            document.getElementById('edit_event').scrollIntoView({
                behavior: 'smooth',
                block: 'start',
                inline: 'start'
            })
        })
    })

    //editar evento
    $(document).on('submit', '#form_edit_event', function(e) {
        e.preventDefault();
        let date = $('#event_date').val()
        if(date) {
            $('#visibility_date').val(date)
        }
        
        let formData = new FormData(this);
        $.ajax({
            url: '../processing/edit_event.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = JSON.parse(response)
                console.log(response)
                if(response.success) {
                    alert('Evento editado com sucesso!');
                    window.location.reload();
                } else {
                    console.log('Erro ao editar o evento: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error)
                console.log('status: ' + status)
                console.log('xhr: ' + xhr)
                console.log('Erro ao enviar para o servidor ' + response)
            }
        })
    })
})