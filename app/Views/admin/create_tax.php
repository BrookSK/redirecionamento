<section class="card">
  <h1>Nova Taxa</h1>
  <form method="post" action="/admin/settings/taxes/create" class="form grid-2">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label><span>Nome</span><input type="text" name="name" required></label>
    <label><span>Tipo</span>
      <select name="type">
        <option value="flat">Fixo</option>
        <option value="percent">Percentual (%)</option>
        <option value="weight">Por Peso (por kg)</option>
      </select>
    </label>
    <label><span>Valor</span><input type="number" step="0.01" name="value" required></label>
    <label><span>Moeda</span>
      <select name="currency">
        <option value="BRL">BRL</option>
        <option value="USD">USD</option>
      </select>
    </label>
    <div class="col-2 right"><button type="submit" class="btn primary">Salvar</button></div>
  </form>
</section>
