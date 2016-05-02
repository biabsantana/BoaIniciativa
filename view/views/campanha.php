
<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."controller/CriadorController.php");
 if(isset($_SESSION['cpf']) && isset($_SESSION['senha'])){
    include("cabecalhologado.php");
 }
 else{
  include("cabecalho.php");
 }

?>
<br><br><br><br>
<div class="container">
  <div class="row">


    <?php
    if (isset($_SESSION['cpf']) && isset($_SESSION['senha'])){
    include("painelDoador.php");
    }

    ?>

    <?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."facade/UsuarioFacade.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."controller/SistemaController.php");

    $id = $_GET['campanha'];
    $campanha = UsuarioFacade::getInstance()->verCampanha($id);
    ?>
    <?php
    if (isset($_SESSION['cpf']) && isset($_SESSION['senha'])){
      echo '<div class="col-md-9 panel panel-default">';
    }else{
      echo '<div class="col-md-12 panel panel-default">';
    }?>
      <h2 class="page-header"><?php echo $campanha->getNome(); ?>
        <?php
        if($campanha->getStatus()){
          echo '<span class="label label-info">Ativa</span>';
        }else{
          echo '<span class="label label-danger">Finalizada</span>';
        }?>
      </span> </h2>

      <div class="col-xs-6">
        <?php if($campanha->getImagem() == "" || $campanha == "default.jpg"){
          echo '<img src="../img/campanha.png" class="img-responsive img-rounded" alt="" />';
        }else{
          echo '<img src="'.$campanha->getImagem().'" class="img-responsive img-rounded" alt="" />';
        } ?>
      </div>


      <div class="row">
        <div class="col-xs-6">
          <h3>Data de inicio: <?php echo date("d/m/Y", strtotime($campanha->getDataInicio()));  ?></h3>
        </div>
        <div class="col-xs-6">
          <h3>Data de Fim: <?php echo date("d/m/Y", strtotime($campanha->getDataFim()));?></h3>
        </div>
        <div class="col-xs-6">
          <h3>Meta:</h3><div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
              70%
            </div>
          </div>
        </div></div><div class="row">
          <img src="../img/logobi.png" class="img-rounded img-responsive center-block" alt="" width="100px" height="100px" />
        </div>
        <div class="row">
          <div class="container-fluid panel panel-success col-md-6" style=" padding:20px;">
            <h2 class="page-header">Descrição</h2>
            <?php echo $campanha->getDescricao(); ?>
            <br/>
            <br/>
            <br/>
          </div>

          <script type="text/javascript" src="../js/campanha.js"></script>

          <div class="container-fluid panel panel-success col-md-6" style="padding:20px;">
            <h2 class="page-header">Ações</h2>
            <a href="usuario.php?cpf=<?php echo $campanha->getCriadorCpf();?>">Ver criador</a><br/>
            <a href="">Convidar Amigo</a><br/>
            <?php if(isset($_SESSION['cpf']) && isset($_SESSION['senha'])){
              echo '<a id="botaoDenunciar">Denunciar</a>';
            } ?>
            <div class="panel panel-primary" id="formDenuncia" >
              <form action="rotas.php" method="post">
                <input type="hidden" name="idCampanha" value="<?php echo $campanha->getIdCampanha(); ?>">
                <input type="hidden" name="cpf" value="<?php echo $_SESSION['cpf']; ?>">
                <div class="form-group col-md-12">
                  <label>Motivo</label><br>
                  <input type="radio" name="motivo" value="Campanha Duvidosa" checked> Campanha Duvidosa <br>
                  <input type="radio" name="motivo" value="Usuário Falso	"> Usuário Falso <br>
                  <input type="radio" name="motivo" value="Informações Incompletas"> Informações Incompletas	<br>

                </div>
                <div class="form-group col-md-12">
                  <label>Descrição</label>
                  <input type="text-area" class="form-control" name="descricao" required placeholder="Descricao">
                </div>
                <div class="row text-center">
                  <div class="col-md-6">
                    <input type="submit" class="btn btn-primary btn-block" style="margin:5px 0px 5px 0px;" name="botaoEnviarDenuncia" value="Denunciar">
                  </div>
                </div>
              </form>

            </div>

          </div>
        </div>


        <div class="panel" style="padding:0px 10px 70px 10px;">
          <center>
            <?php
            if(isset($_SESSION['cpf']) && isset($_SESSION['senha'])){
              $href = "doar.php?idCampanha=".$campanha->getIdCampanha()."&doadorcpf=".$_SESSION['cpf'];
            }
            else{
              $href = "login.php";
            }
            ?>
            <a href="<?php echo $href;?>" class="btn btn-primary col-xs-12 col-md-12 disable">Efetuar Doação</a>
          </center>
        </div>
        <?php
          if(SistemaController::getInstance()->verificarCampanhaMonetaria($campanha->getIdCampanha())){
?>
<!--Para doação monetária-->
<?php $criador = UsuarioDAO::getInstance()->buscarUsuario($campanha->getCriadorCpf());
  $valores = $campanha->getValores();?>
<div class="panel panel-default">
  <h2 class="page-header">Doacao Monetária</h2>
  <!--
  <div class="row">
    <div class="col-md-3">
      <button type="button" id="valor1" class="btn-primary btn btn-lg" value="<?php echo $valores[0].".00";?>" name="valor"><?php echo $valores[0]." R$"; ?></button>
    </div>
    <div class="col-md-3">
      <button type="button" class="btn-primary btn btn-lg" value="<?php echo $valores[1].".00";?>" name="valor"><?php echo $valores[1]." R$"; ?></button>
    </div>
    <div class="col-md-3">
      <button type="button" class="btn-primary btn btn-lg" value="<?php echo $valores[2].".00";?>" name="valor"><?php echo $valores[2]." R$"; ?></button>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label>Valor a ser doado: <font color="FF0000">*</font></label>
        <input id="valordoado" type="number" class="form-control" name="valordoado" placeholder="Valor da doação">
      </div></div>
  </div>
-->
<div class=" text-center img-responsive center-block ">
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
  <input type="hidden" name="cmd" value="_donations">
  <input type="hidden" name="business" value="<?php echo $criador->getEmail(); ?>">
  <input type="hidden" name="lc" value="BR">
  <input type="hidden" name="item_name" value="<?php echo $campanha->getNome(); ?>">
  <input type="hidden" id="paypalvalue" name="amount" >
  <input type="hidden" name="currency_code" value="BRL">
  <input type="hidden" name="no_note" value="0">
  <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
  <input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira fácil e segura de enviar pagamentos online!">
  <img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
</form>
</div>

</div>

<?php
          }
         ?>




      </div>
    </div>
  </div>

  <?php include("footer.php"); ?>
