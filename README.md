# 🎉 GenEvents - Plataforma de Gestão de Eventos

**GenEvents** é um projeto web desenvolvido com o objetivo de facilitar a divulgação, gestão e compra de bilhetes para eventos. Ele permite que utilizadores naveguem por eventos disponíveis, adquiram bilhetes por lote, e que administradores controlem as vendas, os utilizadores e os próprios eventos.

## 🚀 Funcionalidades

- Visualização de eventos com imagens, datas e categorias.
- Compra de bilhetes com limite por lote (ex: Lote 1, Lote 2, etc).
- Carrinho dinâmico.
- Perfil com histórico de compras.
- Área de administração com:
  - Gestão de utilizadores
  - Gestão de eventos
  - Gestão de carrinhos
- Filtros interativos para refinar a pesquisa de eventos.
- Feedback visual de ações (compra, login, mensagens de erro).

## 🛠️ Tecnologias Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript, jQuery, Bootstrap
- **Backend**: PHP com orientado a objetos também.
- **Base de dados**: MySQL
- **Outras ferramentas**:
  - AJAX para requisições assíncronas
  - Docker (ambiente local)
  - phpMyAdmin (gestão da base de dados)

## 🧑‍💻 Como testar o projeto

1. Dois usuários admin salvos:
   - **kevin@admin.com** com senha **Qweasd#3**
   - **admin@admin.com** com senha **Admin123#**
2. Banco de dados:
   - Importar o arquivo .sql que está na raiz do projeto
   - Alterar o $host e $password em php/db.php
   - Alterar o localhost de new PDO em php/dp.php
