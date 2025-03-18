<?php
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'estoque';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro na conexão: ' . $e->getMessage());
}

// Ações CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) { // Adicionar produto
        $nome = $_POST['nome'];
        $quantidade = $_POST['quantidade'];
        $stmt = $pdo->prepare('INSERT INTO produtos (nome, quantidade) VALUES (?, ?)');
        $stmt->execute([$nome, $quantidade]);
    } elseif (isset($_POST['update'])) { // Atualizar produto
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $quantidade = $_POST['quantidade'];
        $stmt = $pdo->prepare('UPDATE produtos SET nome = ?, quantidade = ? WHERE id = ?');
        $stmt->execute([$nome, $quantidade, $id]);
    } elseif (isset($_POST['delete'])) { // Excluir produto
        $id = $_POST['id'];
        $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = ?');
        $stmt->execute([$id]);
    }
}

// Listagem dos produtos
$produtos = $pdo->query('SELECT * FROM produtos')->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Controle de Estoque</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <h1>Controle de Estoque</h1>
            </div>
        </header>

        <main>
            <section class="add-product">
                <h2>Adicionar Novo Produto</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Nome do Produto</label>
                        <input type="text" name="nome" required placeholder="Nome do produto">
                    </div>
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="number" name="quantidade" required min="0" placeholder="Quantidade">
                    </div>
                    <button type="submit" name="add" class="btn-primary">Adicionar Produto</button>
                </form>
            </section>

            <section class="product-list">
                <h2>Lista de Produtos</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome do Produto</th>
                                <th>Quantidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $produto) { ?>
                                <tr>
                                    <td><?= $produto['id'] ?></td>
                                    <td><?= $produto['nome'] ?></td>
                                    <td><span class="quantity-badge"><?= $produto['quantidade'] ?></span></td>
                                    <td class="actions">
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                            <input type="text" name="nome" value="<?= $produto['nome'] ?>" required>
                                            <input type="number" name="quantidade" value="<?= $produto['quantidade'] ?>">
                                            <button type="submit" name="update" class="btn-edit">Editar</button>
                                        </form>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                            <button type="submit" name="delete" class="btn-delete">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
