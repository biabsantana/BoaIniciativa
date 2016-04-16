<?php
include("cabecalhologado.php");
?>
<br><br>
<div class="container">
<div class="row">

  <!--Painel Atendente-->
  <div class="col-md-3 panel panel-default" style="padding:0px 10px 20px 10px;">
    <?php include_once("painelAtendente.php"); ?>
  </div>

<div class="col-md-9 panel panel-default">

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Cadastro Rapido
      </h1>
    </div>
  </div>
<form class="" action="rotas.php" method="post" id="cadastroRapido">
  <div class="row">
    <div class="form-group col-md-6">
      <label > CPF</label>
      <input type="number" class="form-control" name="cpf" required  placeholder="CPF">
    </div>
  </div>
  <div class="row">
    <div class="form-group  col-md-6">
      <label> Senha</label>
      <input type="password" class="form-control" name="password" required placeholder="Password">
    </div>
  </div>
  <div class="row">
    <div class="form-group  col-md-6">
      <label>E-mail</label>
      <input type="email" class="form-control" name="email" required placeholder="Email">
    </div>
  </div>
  <input type="submit" name="botaoCadastroRapido" class="btn btn-primary" value="Criar nova conta!">
</form>


</div>
</div>
</div>
<?php
include("footer.php");
?>
