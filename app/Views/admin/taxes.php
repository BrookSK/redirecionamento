<section>
  <div class="row between">
    <h1>Configurações de Taxas</h1>
    <a class="btn" href="/admin/settings/taxes/create">Nova Taxa</a>
  </div>
  <div class="table-wrap">
  <table class="table">
    <thead><tr><th>ID</th><th>Nome</th><th>Tipo</th><th>Valor</th><th>Moeda</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($taxes as $t): ?>
        <tr>
          <td><?= (int)$t['id'] ?></td>
          <td><?= htmlspecialchars($t['name']) ?></td>
          <td><?= htmlspecialchars($t['type']) ?></td>
          <td><?= (float)$t['value'] ?></td>
          <td><?= htmlspecialchars($t['currency']) ?></td>
          <td class="right">
            <a class="btn" href="/admin/settings/taxes/edit/<?= (int)$t['id'] ?>">Editar</a>
            <form method="post" action="/admin/settings/taxes/delete/<?= (int)$t['id'] ?>" style="display:inline">
              <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
              <button class="btn" type="submit">Excluir</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</section>
