<section class="card">
  <h1>Editar Pacote #<?= (int)$p['id'] ?></h1>
  <form method="post" action="/admin/packages/edit/<?= (int)$p['id'] ?>" class="form grid-2">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label><span>Suite Number</span><input type="text" name="suite_number" value="<?= htmlspecialchars($p['suite_number']) ?>" required></label>
    <label><span>Produto</span><input type="text" name="product_name" value="<?= htmlspecialchars($p['product_name']) ?>"></label>
    <label><span>Fornecedor</span><input type="text" name="supplier" value="<?= htmlspecialchars($p['supplier']) ?>"></label>
    <label><span>NCM</span><input type="text" name="ncm" value="<?= htmlspecialchars($p['ncm']) ?>"></label>
    <label><span>Recebido em</span><input type="date" name="received_date" value="<?= htmlspecialchars($p['received_date']) ?>"></label>
    <label><span>Peso (kg)</span><input type="number" step="0.01" name="weight" value="<?= (float)$p['weight'] ?>"></label>
    <label><span>Quantidade</span><input type="number" name="quantity" value="<?= (int)$p['quantity'] ?>"></label>
    <label class="col-2"><span>Foto URL</span><input type="text" name="photo_url" value="<?= htmlspecialchars($p['photo_url']) ?>"></label>
    <label class="col-2"><span>Status</span>
      <select name="status">
        <?php $st = $p['status'] ?? 'Pendente'; ?>
        <option value="Pendente" <?= $st==='Pendente'?'selected':'' ?>>Pendente</option>
        <option value="Pedido Criado" <?= $st==='Pedido Criado'?'selected':'' ?>>Pedido Criado</option>
        <option value="Enviado" <?= $st==='Enviado'?'selected':'' ?>>Enviado</option>
      </select>
    </label>
    <div class="col-2 right"><button type="submit" class="btn primary">Salvar</button></div>
  </form>
</section>
