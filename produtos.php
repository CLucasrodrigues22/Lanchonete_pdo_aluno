<?php include './layout/header.php'; ?>
<?php include './layout/menu.php'; ?>
<?php 
$permissoes = retornaControle('produto');
if(empty($permissoes)) {
	header("Location: adminstrativa.php?msg=Acesso negado.");
}
require 'classes/Categoria.php';
require 'classes/Produto.php';
require 'classes/CategoriaDAO.php';
require 'classes/ProdutoDAO.php';

$produtoDAO = new ProdutoDAO();
$categoriaDAO = new CategoriaDAO();
if(isset($_GET['pesquisa']) && $_GET['pesquisa'] != '') {
	$produtos = $produtoDAO->listar($_GET['pesquisa']);
} else {
	$produtos = $produtoDAO->listar();
}

?>
<div class="row" style="margin-top:40px">
	<div class="col-6">
		<h2>Gerenciar produtos</h2>
	</div>
	<div class="col-4">
	<form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" name="pesquisa" type="search" placeholder="Pesquisar" aria-label="Pesquisar" value="<?= (isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '') ?>">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
      	<i class="fas fa-search"></i>	
      </button>
      <a href="./produtos.php" class="btn btn-outline-warning my-2 my-sm-0">
      	<i class="fas fa-trash-alt"></i>
      </a>
    </form>
	</div>
	<?php if($permissoes['insert']): ?>
	<div class="col-2">
		<a href="form_produto.php" class="btn btn-success">Novo</a>
	</div>
	<?php endif; ?>
</div>
<div class="row">
	<table class="table table-hover table-bordered table-striped table-responsive-lg">
		<thead>
			<tr>
				<th>#ID</th>
				<th>Nome</th>
				<th>Preço</th>
				<th>Categoria</th>
				<th>Qtd</th>
				<th>Descrição</th>
				<th>Ações</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($produtos as $produto){ 
				$categoria = $categoriaDAO->get($produto->getCategoria()); 
			?>
				<tr
					<?php if($produto->getQtd() < 1) {
						echo 'class="table-danger"';
					} else if($produto->getQtd() < 5) {
						echo 'class="table-warning"';
					}
					?>
				>
					<td><?= $produto->getId() ?></td>
					<td><?= $produto->getNome() ?></td>
					<td>R$ <?= $produto->getPreco() ?></td>
					<td><?= $categoria->getNome() ?></td>
					<td><?= $produto->getQtd() ?></td>
					<td data-toggle="tooltip" title="<?= ($produto->getDescricao() != '' ? $produto->getDescricao() : ''); ?>"><?= ($produto->getDescricao() != '' ? substr($produto->getDescricao(),0,15).'...' : ''); ?></td>
					<td>
						<?php if($permissoes['update'] || $permissoes['show']): ?>
						<a href="form_produto.php?id=<?= $produto->getId() ?>" class="btn btn-warning" data-toggle="tooltip" title="Exibir/Editar produto">
							<i class="fas fa-edit"></i>
						</a>
						<?php endif; ?>
						<?php if($permissoes['delete']): ?>
						<a href="controle_produto.php?acao=deletar&id=<?= $produto->getId() ?>" onclick="return confirm('Deseja realmente excluir?')" class="btn btn-danger" data-toggle="tooltip" title="Excluir produto">
							<i class="fas fa-trash-alt"></i>
						</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<?php include './layout/footer.php'; ?>