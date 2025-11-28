<section>
  <div class="row between">
    <h1>Pacotes</h1>
    <a class="btn" href="/admin/packages/create">Cadastrar Pacote</a>
  </div>
  <form method="get" action="/admin/packages" class="card row" style="gap:12px;align-items:flex-end;margin-bottom:16px">
    <label><span>Suite</span><input type="text" name="suite_number" value="<?= htmlspecialchars($filters['suite_number'] ?? '') ?>"></label>
    <label><span>Status</span>
      <select name="status">
        <?php $st = $filters['status'] ?? ''; ?>
        <option value="">Todos</option>
        <option value="Pendente" <?= $st==='Pendente'?'selected':'' ?>>Pendente</option>
        <option value="Pedido Criado" <?= $st==='Pedido Criado'?'selected':'' ?>>Pedido Criado</option>
        <option value="Enviado" <?= $st==='Enviado'?'selected':'' ?>>Enviado</option>
      </select>
    </label>
    <label><span>Recebido de</span><input type="date" name="received_from" value="<?= htmlspecialchars($filters['received_from'] ?? '') ?>"></label>
    <label><span>Recebido até</span><input type="date" name="received_to" value="<?= htmlspecialchars($filters['received_to'] ?? '') ?>"></label>
    <button class="btn primary" type="submit">Filtrar</button>
  </form>
  <div class="table-wrap">
  <table class="table">
    <thead>
      <tr>
        <th>ID</th><th>Suite</th><th>Produto</th><th>Status</th><th>Ações</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($packages as $p): ?>
      <tr>
        <td><?= (int)$p['id'] ?></td>
        <td><?= htmlspecialchars($p['suite_number']) ?></td>
        <td><?= htmlspecialchars($p['product_name']) ?></td>
        <td><?= htmlspecialchars($p['status']) ?></td>
        <td class="right"><a class="btn" href="/admin/packages/edit/<?= (int)$p['id'] ?>">Editar</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php if (($pages ?? 1) > 1): ?>
    <div class="row" style="margin-top:10px">
      <?php for ($i=1; $i<=$pages; $i++): ?>
        <?php
          $qs = $_GET;
          $qs['page'] = $i;
          $href = '/admin/packages?' . http_build_query($qs);
        ?>
        <a class="btn<?= ($page ?? 1) === $i ? ' primary' : '' ?>" href="<?= htmlspecialchars($href) ?>" style="margin-right:6px"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</section>
