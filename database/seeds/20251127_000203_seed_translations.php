<?php
return function (\PDO $pdo) {
    $ins = $pdo->prepare("INSERT OR REPLACE INTO translations(key, pt_br, en_us) VALUES (?,?,?)");
    $rows = [
        ['login_title','Entrar','Sign In'],
        ['email','E-mail','Email'],
        ['password','Senha','Password'],
        ['sign_in','Entrar','Login'],
        ['logout','Sair','Logout'],
        ['admin_dashboard','Dashboard Admin','Admin Dashboard'],
        ['client_dashboard','Dashboard do Cliente','Client Dashboard'],
        ['packages','Pacotes','Packages'],
        ['orders','Pedidos','Orders'],
        ['cart','Carrinho','Cart'],
        ['checkout','Checkout','Checkout']
    ];
    foreach ($rows as $r) { $ins->execute($r); }
};
