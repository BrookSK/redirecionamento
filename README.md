# Package Forwarding (PHP MVC)

Sistema de redirecionamento de encomendas em PHP puro com MVC customizado.

## Requisitos
- PHP 8.0+ com PDO e SQLite habilitados
- Windows (testado) ou outro OS com PHP

## Como rodar (dev)
1. Abra um terminal na pasta do projeto.
2. Inicie o servidor embutido do PHP:
   
   php -S localhost:8000 server.php
   
3. Acesse: http://localhost:8000
4. Alternativa: acesse a raiz (index.php redireciona para /public/)

## Acesso inicial (seed)
- Admin: admin@example.com / admin123
- Cliente: cliente@example.com / cliente123

## Fluxo principal
- Admin cadastra pacote: /admin/packages/create
- Cliente vê pacotes pendentes em /client/dashboard
- Carrinho: /cart (preenche valor de declaração)
- Checkout: /checkout (seleciona moeda BRL/USD, pagamento simulado)
- Pedidos: /client/orders

## Idiomas e Moedas
- Idiomas: PT (default) e EN. Troca via /lang/pt_BR ou /lang/en_US
- Moedas: BRL e USD. Preferência do cliente em /currency/BRL ou /currency/USD

## Configurações
- Arquivo: config/app.php
- DB: SQLite em storage/database.sqlite (criado automaticamente)
- Taxas: tabela tax_settings (semeada com exemplos)

## Estrutura
- public/index.php: Front Controller + rotas
- app/Core: Router, Controller, View, Database, Translation, Auth
- app/Controllers: Auth, Admin, Client, Cart, Checkout
- app/Views: layout + telas (login, admin, client, cart, checkout)
- database/migrations: migrations baseadas em arquivos (executadas no bootstrap)
- database/seeds: seeds baseadas em arquivos (executadas no bootstrap)

## Migrations e Seeds
- As migrations e seeds são executadas automaticamente no bootstrap (public/index.php) via `Migrator::run()` e `Seeder::run()`.
- Adicione novos arquivos em:
  - `database/migrations/YYYMMDD_HHMMSS_nome.php`
  - `database/seeds/YYYMMDD_HHMMSS_nome.php`
- Cada arquivo deve retornar um callable que recebe `PDO $pdo` e executa as alterações/inserts.
- Regra: sempre criar novas migrations/seeds (nunca editar existentes).

## Observações
- Pagamentos: simulados (Appmax p/ BRL, Stripe p/ USD)
- Conversão de moeda: valores calculados conforme moeda escolhida; lógica poderá ser refinada.
- Segurança: CSRF aplicado em formulários de POST (login, registro, carrinho, checkout, pacotes, taxas). Delete de taxas via POST.
- Logs: em storage/logs/app.log (login/logout, criação/edição de pacotes, taxas, pagamento).
- Admin/Pacotes: filtros e paginação.
- Models: adicionados BaseModel, User, Package, Cart, Order (uso opcional).
- Raiz: index.html redireciona para /public/.
