<?php include_once("cabecalhologado.php"); ?>

<br><br><br><br>
<div class="container">


<div class="row">

  <?php include_once("painelCriador.php"); ?>

  <div class="col-md-9 panel panel-default">


    <body>
      <div>

        <div class="col-md-12">
          <div class="row">
            <div class="col-md-12">
              <h1 class="page-header">Criar Nova Campanha
              </h1>
            </div>
          </div>

          <form method="post" action="rotas.php" id="formcadastro" name="formcadastro">
            <div class="panel panel-default col-md-12">
              <h4 class="page-header">Informações da campanha</h4>
              <div class="form-group">
                <label>Nome da campanha <font color="red">*</font></label>
                <input type="text" class="form-control" name="nome" required  placeholder="Nome da campanha">
              </div>
              <div class="form-group">
                <label> Descrição <font color="red">*</font></label>
                <input type="text" rows="4" class="form-control" name="descricao" required placeholder="Descrição da Campanha">
              </div>
              <div class="form-group">
                <label> Item de arrecadação <font color="red">*</font> </label>
                <input type="text" class="form-control" name="possiveisArrecadacoes" required placeholder="Arrecadação">
              </div>
              <div class="form-group">
                <label>Data de Início da Campanha <font color="red">*</font></label>
                <input type="date" class="form-control" name="dataInicio" required placeholder="Formato: dd/mm/aaaa">
              </div>
              <div class="form-group">
                <label>Data Final da campanha <font color="red">*</font></label>
                <input type="date" class="form-control" name="dataFim" required placeholder="Formato: dd/mm/aaaa">
              </div>
              <div class="form-group">
                <label>Data esperada para atingir a meta <font color="red">*</font></label>
                <input type="date" class="form-control" name="dataMeta" placeholder="Formato: dd/mm/aaaa">
              </div>
              <div class="form-group">
                <label>Agradecimento padrão<font color="red">*</font></label>
                <input type="text" class="form-control" name="agradecimento" required placeholder="Agradecimento da campanha">
              </div>
              <div class="form-group">
                <label>Imagem da campanha</label>
                <input type="file" name="imagem">
              </div>
            </div>
            <input type="submit" name="botaoCadastrarCampanha" class="btn btn-primary" value="Criar campanha!">
          </form>
          <br>
        </div>

      </div>
  </div>
</div>

</div>
<?php include_once("footer.php"); ?>

  <?php include_once("footer.php"); ?>

</body>
