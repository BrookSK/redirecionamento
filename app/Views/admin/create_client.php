<section class="card">
  <h1>Cadastrar Cliente</h1>
  <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post" action="/admin/clients/create" class="form grid-2">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label><span>Nome</span><input type="text" name="name" required></label>
    <label><span>Sobrenome</span><input type="text" name="last_name"></label>
    <label><span>E-mail</span><input type="email" name="email" required></label>
    <label><span>Suite</span><input type="text" name="suite_number" required></label>
    <label><span>Senha</span><input type="password" name="password" required></label>

    <h2 class="col-2">Endereço de Cobrança</h2>
    <label><span>Rua</span><input type="text" name="billing_street"></label>
    <label><span>Número</span><input type="text" name="billing_number"></label>
    <label><span>Bairro</span><input type="text" name="billing_neighborhood"></label>
    <label><span>Cidade</span><input type="text" name="billing_city"></label>
    <label><span>Estado</span><input type="text" name="billing_state"></label>
    <label><span>País</span><input type="text" name="billing_country" value="Brasil"></label>
    <label><span>Complemento</span><input type="text" name="billing_complement"></label>
    <label><span>CEP</span><input type="text" name="billing_zip"></label>
    <label class="col-2"><span>Celular</span><input type="text" name="billing_phone"></label>

    <h2 class="col-2">Endereço de Entrega</h2>
    <label><span>Rua</span><input type="text" name="shipping_street"></label>
    <label><span>Número</span><input type="text" name="shipping_number"></label>
    <label><span>Bairro</span><input type="text" name="shipping_neighborhood"></label>
    <label><span>Cidade</span><input type="text" name="shipping_city"></label>
    <label><span>Estado</span><input type="text" name="shipping_state"></label>
    <label><span>País</span><input type="text" name="shipping_country" value="Brasil"></label>
    <label><span>Complemento</span><input type="text" name="shipping_complement"></label>
    <label><span>CEP</span><input type="text" name="shipping_zip"></label>
    <label class="col-2"><span>Celular</span><input type="text" name="shipping_phone"></label>

    <h2 class="col-2">Dados Pessoais</h2>
    <label><span>CPF</span><input type="text" name="cpf"></label>
    <label><span>Data de Nascimento</span><input type="date" name="birth_date"></label>

    <div class="col-2 right"><button type="submit" class="btn primary">Salvar</button></div>
  </form>
</section>
