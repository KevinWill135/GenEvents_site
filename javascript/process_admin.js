$(document).ready(function() {
    //carregar view total_sales
    function viewsData() {
        $.ajax({
            url: '../processing/process_admin.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                data.sales.forEach(sale => {
                    let total_sales = $('#total_sales')  
                    let tr = `
                        <tr>
                            <td>
                                ${sale.user_id}
                            </td>
                            <td>
                                ${sale.name}
                            </td>
                            <td>
                                ${sale.total_sales}
                            </td>
                        </tr>
                    `
                    total_sales.append(tr)
                })
                data.orders.forEach(history => {
                    let order_history = $('#order_history')
                    let tr = `
                        <tr>
                            <td>${history.sale_id}</td>
                            <td>${history.user_id}</td>
                            <td>${history.username}</td>
                            <td>${history.event_name}</td>
                            <td>${history.batch_name}</td>
                            <td>${history.quantity}</td>
                            <td>${history.unit_price}</td>
                            <td>${history.total_price}</td>
                            <td>${history.sale_date}</td>
                    `
                    order_history.append(tr)
                })
                //console.log(data)
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar o carrinho: ', error)
                console.error('Erro xhr: ', xhr.responseText)
                console.error('Status: ', status)
            }
        })

    }
    viewsData()

    //carregar usuários
    function usersTable() {
        $.ajax({
            url: '../processing/process_users.php',
            method: 'POST',
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, user) {
                    let user_tbody = $('#user_tbody')
                    let tr = `
                        <tr>
                            <td>
                                <img src="../processing/${user.user_img}" class="img-fluid img_adm">
                            </td>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>Senha Criptografada</td>
                            <td>${user.phone}</td>
                            <td>${user.city}</td>
                            <td>${user.country}</td>
                            <td>${user.role}</td>
                            <td>${user.event_type}</td>
                            <td>
                                <button type="button" class="btn btn-outline-dark select_user" data-id="${user.id}">
                                    Selecionar
                                </button>
                            </td>
                        </tr>
                    `
                    user_tbody.append(tr)
                })
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error)
                console.log('Status: ' + status)
                console.log('XHR: ' + xhr)
            }
        })
    }
    usersTable()

    //selecionando usuário para atualizar
    $(document).on('click', '.select_user', function() {
        let id_selected = $(this).data('id')
        console.log(id_selected)
        $.post('../processing/get_user.php', { id_selected: id_selected }, function(html) {
            $('#edit_user').html(html)
            $('<input>', {
                type: 'hidden',
                name: 'user_id',
                value: id_selected
            }).appendTo('#form_adm')
            $('#edit_user').css('display', 'block')
        })
    })

    //carregar tabela simples de usuário para selecionar e ver seu carrinho
    function usersCart() {
        $.ajax({
            url: '../processing/process_users.php',
            method: 'POST',
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, user) {
                    let userCart_tbody = $('#userCart_tbody')
                    let tr = `
                        <tr>
                            <td>
                                <img src="../processing/${user.user_img}" class="img-fluid img_adm">
                            </td>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td>
                                <button type="button" class="btn btn-outline-dark select_cart" data-id="${user.id}">
                                    Selecionar
                                </button>
                            </td>
                        </tr>
                    `
                    userCart_tbody.append(tr)
                })
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error)
                console.log('Status: ' + status)
                console.log('XHR: ' + xhr)
            }
        })
    }
    usersCart()

    //mandando o user selecionando em users_cart para o back-end
    $(document).on('click', '.select_cart', function() {
        let user_id = $(this).data('id')
        console.log(user_id)
        $.post('../processing/get_cart.php', { user_id: user_id }, function(html) {
            $('#user_cart').html(html)
            $('<input>', {
                type: 'hidden',
                name: 'user_id',
                value: user_id
            }).appendTo('#user_cart')
        })
    })

    //Removendo do carrinho através do users_cart
    $(document).on('click', '.remover_cart_admin', function() {
        let cart_id = $(this).data('cart-id')
        //console.log(cart_id)
        $.post('../processing/delete_cart.php', { cart_id: cart_id }, function(response) {
            alert('Removido com Sucesso!')
            window.location.href = 'users_cart.php'
        })
    })

    //atualizando quantidade no banco de dados e o total no front
    function updatePrice(card) {
        let qtd_cart = parseInt($(card).find('.qtd_cart_admin').val())
        let price = parseFloat($(card).find('.qtd_cart_admin').data('price'))
        let total = (price * qtd_cart).toFixed(2)
        $(card).find('.total_adm').html(total)
    }

    $('.cart_adm').each(function() {
        updatePrice(this)
    })

    $(document).on('change', '.qtd_cart_admin', function() {
        let card = $(this).closest('.cart_adm')
        updatePrice(card)

        //recuperando dados para mudar em database
        let user_id = $(this).data('user')
        let batch_id = $(this).data('id')
        let qtd_cart = $(this).val()

        $.ajax({
            url: '../processing/update_cart.php',
            method: 'POST',
            data: {new_qtd: qtd_cart, batch_id: batch_id, user_id: user_id},
            success: function(response) {
                if(response.success) {
                    console.log('dados enviados')
                } else {
                    console.log('erro ao enviar dados no process_admin ' + response.message)
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
})