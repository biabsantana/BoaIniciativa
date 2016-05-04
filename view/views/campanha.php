
<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."controller/CriadorController.php");
session_start();
if( isset($_SESSION['cpf']) && isset ($_SESSION['senha']) ){//Verifica se já está logado
  include("cabecalhologado.php");
}else {
  include("cabecalho.php");
}

?>

<br><br>
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
    require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."facade/SistemaFacade.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."facade/PerfilFacade.php");

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
    <div class="row">

      <!--Imagem-->
      <div class="col-xs-6">
        <?php if($campanha->getImagem() == "" || $campanha == "default.jpg"){
          echo '<img src="../img/campanha.png" class="img-responsive img-rounded" alt="" />';
        }else{
          echo '<img src="'.$campanha->getImagem().'" class="img-responsive img-rounded" alt="" />';
        } ?>
      </div>

      <div class="col-12-6 col-md-6">
        <div class="col-xs-12">
          <h3>Data de inicio: <?php echo date("d/m/Y", strtotime($campanha->getDataInicio()));  ?></h3>
        </div>
        <div class="col-xs-12">
          <h3>Data de Fim: <?php echo date("d/m/Y", strtotime($campanha->getDataFim()));?></h3>
        </div>
        <?php if(!$campanha->getFinalizaPorData()){ ?>
        <div class="col-xs-12">
          <h3>Meta:</h3>
          <?php SistemaFacade::getInstance()->printProgressMeta($campanha->getIdCampanha()); ?>
        </div>
        <?php } ?>
      </div>
    </div>


    <div class="row">
      <div class=" panel panel-success col-md-6" style=" padding:20px;">
        <h2 class="page-header">Descrição</h2>
        <?php echo $campanha->getDescricao(); ?>

      </div>

      <script type="text/javascript" src="../js/campanha.js"></script>

      <div class=" panel panel-success col-md-6" style="padding:20px;">
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

    <?php 
      $atendentes = CriadorFacade::getInstance()->listarAtendentesCampanha($id);
      $lat = array();
      $long = array();
      for ($i = 0; $i < count($atendentes); $i++) { 
        $usr = PerfilFacade::getInstance()->buscarUsuario($atendentes[$i]);
        $lat = $usr->getLatitude();
        $long = $usr->getLongitude();
      }
      $lat = implode("|", $lat);
      $long = implode("|", $long);
    ?>

    <div id="mapaPostos"></div>


    <script type='text/javascript'>
      var map;
      function initialize(){
      var lat = "<?php echo $lat;?>";
      lat = lat.split("|");
      var lng = "<?php echo $long;?>";
      lng = lng.split("|");
      if(getLocation())
        navigator.geolocation.getCurrentPosition(showPosition);
      else{
        var latlng = new google.maps.LatLng(-18.8800397, -47.05878999999999);
        var options = {
          zoom: 5,
          center: latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
      }
      map = new google.maps.Map(document.getElementById("mapaPostos"), options);
      for (var i = 0; i < lat.length; i++) {
        marcacaoEndereco(lat[i], lng[i]);
      };      

      function getLocation(){
        if(navigator.getLocation)   
          return true;
        else
          return false;
      }
      function showPosition(position){
        var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        var options = {
          zoom: 5,
          center: latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
      }
      function marcacaoEndereco(lat, lng){
        var ponto = new google.maps.LatLng(lat,lng);
        var marker = new google.maps.Marker({position: ponto, map: map});
      }
    }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtrcCnCC71lEBRbj-hM5KwlHcSppnenBI&callback=initialize"async defer></script>

    

    <div class="panel" style="padding:0px 10px 70px 10px;">
      <center>
        <?php
        if(isset($_SESSION['cpf'])){
          if($_SESSION['cpf'] == $campanha->getCriadorCpf()){
            echo "<a href='editarCampanha.php?campanha=".$campanha->getIdCampanha()."' class='btn btn-primary col-xs-12 col-md-12 disable'>Editar Campanha</a>";
            echo "<a href='gerarRelatorio.php?campanha=".$campanha->getIdCampanha()."' class='btn btn-primary col-xs-12 col-md-12 disable'>Gerar Relatórios</a>";
            echo "<a href='excluirCampanha.php?campanha=".$campanha->getIdCampanha()."' class='btn btn-primary col-xs-12 col-md-12 disable'>Excluir Campanha</a>";
          }
        }
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

<div class="container">
<?php include("footer.php"); ?>
</div>
