<section class="card">
  <h1>Editar Taxa #<?= (int)$t['id'] ?></h1>
  <form method="post" action="/admin/settings/taxes/edit/<?= (int)$t['id'] ?>" class="form grid-2">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label><span>Nome</span><input type="text" name="name" value="<?= htmlspecialchars($t['name']) ?>" required></label>
    <label><span>Tipo</span>
      <select name="type">
        <option value="flat" <?= $t['type']==='flat'?'selected':'' ?>>Fixo</option>
        <option value="percent" <?= $t['type']==='percent'?'selected':'' ?>>Percentual (%)</option>
        <option value="weight" <?= $t['type']==='weight'?'selected':'' ?>>Por Peso (por kg)</option>
      </select>
    </label>
    <label><span>Valor</span><input type="number" step="0.01" name="value" value="<?= (float)$t['value'] ?>" required></label>
    <label><span>Moeda</span>
      <select name="currency">
        <option value="BRL" <?= $t['currency']==='BRL'?'selected':'' ?>>BRL</option>
        <option value="USD" <?= $t['currency']==='USD'?'selected':'' ?>>USD</option>
      </select>
    </label>
    <div class="col-2 right"><button type="submit" class="btn primary">Salvar</button></div>
  </form>
</section>
