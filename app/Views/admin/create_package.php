<section class="card">
  <h1>Cadastrar Pacote</h1>
  <form method="post" action="/admin/packages/create" class="form grid-2">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label><span>Suite Number</span><input type="text" name="suite_number" required></label>
    <label><span>Produto</span><input type="text" name="product_name"></label>
    <label><span>Fornecedor</span><input type="text" name="supplier"></label>
    <label><span>NCM</span><input type="text" name="ncm"></label>
    <label><span>Recebido em</span><input type="date" name="received_date"></label>
    <label><span>Peso (kg)</span><input type="number" step="0.01" name="weight"></label>
    <label><span>Quantidade</span><input type="number" name="quantity" value="1"></label>
    <label class="col-2"><span>Foto URL</span><input type="text" name="photo_url"></label>
    <div class="col-2 right"><button type="submit" class="btn primary">Salvar</button></div>
  </form>
</section>
