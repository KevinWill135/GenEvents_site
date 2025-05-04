$(document).ready(function() {
    //função para chamar o alert
    function showAlert(message) {
        let div_alert = $('#div_alert')
        let time = 5
        div_alert.html(`
            <div class="alert alert-${message} alert-dismissible" role="alert">
                <div id="alert">Seu carrinho está vazio, serás redirecionado para Home em <span id="time">${time}</span>s</div>
            </div>
        `)
        const interval = setInterval(() => {
            $('#time').text(time);
            time--;

            if(time < 0) {
                clearInterval(interval)
            }
        }, 1000)

        div_alert.css({
            'position':'absolute', 
            'z-index':'1000',
            'display': 'flex'
        })

        $('#body_cart').css({
            'background-color': 'rgba(0, 0, 0, 0.5)',
            'z-index': '1',
        })

        $('.header_container').addClass('alert_definition')
            

        setTimeout(() => {
            window.location.href = 'index.php'
        }, 6000)
    }

    //atualizar total no carrinho
    function updateTotal() {
        total = 0
        $('.qtd_cart').each(function() {
            let qtd_cart = $(this).val()
            let price_spn = $(this).data('price')
            if(isNaN(price_spn) || isNaN(qtd_cart)) {
                console.log('Uma das variáveis não é do tipo número')
            }
            total += price_spn * qtd_cart
    
        })
        $('#total_ticket').text(total.toFixed(2));
        let total_ticket = $('#total_ticket').html()
        if(total_ticket == '0.00') {
            /*$('#cart_section').html(`
                    <div>
                        <h4 class="text-danger">Carrinho Vazio</h4>
                    </div>
                `)*/

            showAlert('danger')
            //window.location.href = 'index.php'
        }
    }
    updateTotal()
    

    //alterar quantity no banco de dados
    $('.qtd_cart').on('change', function() {
        let qtd_cart = $(this).val()
        let batch_id = $(this).data('id')
        let user_id = $(this).data('user')
        updateTotal()
        
        //console.log(price_spn)
        //console.log(qtd_cart)
        //console.log(batch_id)
        
        $.ajax({
            url: '../php/processing/update_cart.php',
            method: 'POST',
            data: {new_qtd: qtd_cart, batch_id: batch_id, user_id: user_id},
            success: function(response) {
                if(response.success) {
                    console.log('dados enviados')
                } else {
                    console.log('erro ao enviar dados!! ' + response.message)
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao atulizar o carrinho: ', error)
                console.error('Erro xhr: ', xhr)
                console.error('Status: ', status)
                console.log('Erro de conexão com servidor')
            }
        })

    })

    //remover ticket
    $('.remover_cart').on('click', function() {
        let id = $(this).data('cart-id')
        
        $.ajax({
            url: '../php/processing/delete_cart.php',
            method: 'POST',
            data: {cart_id: id},
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    console.log('ID enviado com sucesso ' + response.message)
                    $(`[data-cart-id="${id}"]`).closest('.card').remove()
                    updateTotal()
                } else {
                    console.log('Erro ao enviar o ID ' + response.message)
                }
            },
            error: function(xhr, status, error) {
                console.log('ERROR: ' + error)
                console.log('STATUS: ' + status)
                console.log('XHR: ' + xhr)
                console.log('Erro ao enviar os dados para o servidor')
            }
        })
    })

    //finalizar compra
    $('#cart_form').submit(function(event) {
        event.preventDefault()

        let cartItems = []
        $('.qtd_cart').each(function() {
            let quantity = parseInt($(this).val())
            let batch_id = $(this).data('id')
            let unit_price = parseFloat($(this).data('price'))
            let event_id = $(this).data('event-id')

            if(quantity > 0) {
                cartItems.push({
                    event_id: event_id,
                    batch_id: batch_id,
                    quantity: quantity,
                    unit_price: unit_price
                })
            }
        })

        let total_price = 0
        cartItems.forEach(item => {
            total_price += item.quantity * item.unit_price
        })

        $.ajax({
            url: '../php/processing/finalize_cart.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                items: cartItems,
                total_price: total_price
            }),
            success: function(response) {
                console.log('Response no success ' + response)
                if(response.success) {
                    alert('Compra finalizada com sucesso')
                    window.location.href = 'index.php'
                } else {
                    alert('Erro ao receber o finalizar compra ' + response.message)
                }
            },
            error: function(xhr, status, error) {
                console.log('ERROR: ' + error)
                console.log('STATUS: ' + status)
                console.log('XHR: ' + xhr)
                console.log('Erro ao enviar os dados para o servidor')
            }
        })

    })
})