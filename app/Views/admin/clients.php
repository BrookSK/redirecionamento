<section>
  <div class="row between">
    <h1>Clientes</h1>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Role</th>
          <th>Suite</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($clients as $c): ?>
        <tr>
          <td><?= (int)$c['id'] ?></td>
          <td><?= htmlspecialchars($c['name']) ?></td>
          <td><?= htmlspecialchars($c['email']) ?></td>
          <td><?= htmlspecialchars($c['role']) ?></td>
          <td><?= htmlspecialchars($c['suite_number']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
