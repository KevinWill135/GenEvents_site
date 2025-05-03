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
                                <img src="../processing/${user.user_img}" class="img-fluid" width="100">
                            </td>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>Senha</td>
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
        })
    })

})