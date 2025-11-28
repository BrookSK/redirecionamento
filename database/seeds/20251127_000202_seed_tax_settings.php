<?php
return function (\PDO $pdo) {
    $ins = $pdo->prepare("INSERT INTO tax_settings(name,type,value,currency) VALUES (?,?,?,?)");
    $ins->execute(['Serviço', 'flat', 10, 'BRL']);
    $ins->execute(['Manuseio', 'percent', 5, 'BRL']);
    $ins->execute(['Peso', 'weight', 2, 'BRL']);
    $ins->execute(['Service', 'flat', 5, 'USD']);
};
