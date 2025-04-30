$(document).ready(function() {
    //atualizar total
    function updateTotal() {
        total = 0;
        $('.qtd_batch').each(function() {
            const price = parseFloat($(this).data('price'))
            const qtd_input = parseInt($(this).val()) || 0;
            total += price * qtd_input
        })
        $('#total').text(total.toFixed(2))
    }

    //atualizar sempre o total
    $('.qtd_batch').on('change', updateTotal)

    //enviando dados com button
    $('#add_cart').click(function() {
        const data = [];

        $('.qtd_batch').each(function() {
            let qtd_input = parseInt($(this).val())
            if(qtd_input > 0) {
                data.push({
                    batch_id: $(this).data('batch-id'),
                    qtd_input: qtd_input
                })
            }
        })
        console.log(data)

        if(data.length === 0) {
            alert('Selecione ao menos um ingresso.')
            return;
        }

        $.ajax({
            url: '../php/processing/add_cart.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                if(response.success) {
                    $('.qtd_batch').val(0)
                    updateTotal()
                    window.location.href = 'cart.php'
                } else {
                    alert('Erro line 47 process_event: ' + response.message)
                    $('.qtd_batch').val(0)
                    updateTotal()
                }
            },
            error: function(xhr, status, error) {
                console.log('ERROR: ' + error)
                console.log('STATUS: ' + status)
                console.log('XHR: ' + xhr)
                console.log('Erro ao enviar os dados')
            }
        })
    })

})