<?php
include("cabecalhologado.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."controller/AtendenteController.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."controller/UsuarioController.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."database/CampanhaDAO.php");


if(isset($_GET['campanha'])){
  $idCampanha = $_GET['campanha'];
  $autenticacao = AtendenteController::getInstance()->autenticarAtendente($_SESSION['cpf'],$idCampanha);

  ?>
  <br><br><br><br>
  <div class="container">
    <div class="row">

      <div class="col-md-3 panel panel-default" style="padding:0px 10px 20px 10px;">
        <?php          include("painelAtendente.php"); ?>
      </div>
      <script type="text/javascript" src="../js/atender.js"></script>

      <?php
      echo '<div class="col-md-9 panel panel-default">';
      if($autenticacao){//Usuario atende na campanha
        ?>
        <h2 class="page-header">Atendimento na campanha <?php echo CampanhaDAO::getInstance()->buscarCampanha($idCampanha)->getNome(); ?></h2>
        <div class="row">
          <div class="col-md-6 panel panel-success">
            <div class="row">
              <div class="col-md-12">
                <h3 class="page-header"> Digite o CPF do usuário para confirmar a doação</h3>
              </div>
              <form  name="formVerDoacoes" id="formVerDoacoesCPF" action="atender.php">
                <input type="hidden" id="idCampanha" name="campanha" value="<?php echo $idCampanha; ?>">
                <div class="col-md-12 row">
                  <div class="form-group">
                    <div class="col-md-6">
                      <label > CPF</label>
                      <input type="number" id="cpfUsuario" class="form-control" name="cpf" required  placeholder="CPF">
                    </div>
                    <div class="col-md-6">
                      <div id="cpfUsuarioerror"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <a id="botaoVerDoacoes" class="btn btn-block btn-primary" > Ver Doações</a>
                </div>
              </form>
            </div>

          </div>
          <?php if(isset($_GET['cpf'])){//Se o cpf do usuário for setado
            echo '<div class="col-md-6 panel panel-success">';
            $usuario = UsuarioController::getInstance()->buscarUsuario($_GET['cpf']);
            if($usuario->getCpf() != null){
              echo '<h2 class="page-header">Doações de ';
              if($usuario->getNome() == ""){
                echo $usuario->getCpf();
              }else{
                echo $usuario->getNome();
              }
              echo'</h2>';
              $doacoes = AtendenteController::getInstance()->buscarDoacoesPendentesNaCampanha($_GET['cpf'],$_GET['campanha']);
              if(sizeof($doacoes) == 0){
                echo '<div class="alert alert-warning row alert-dismissible" role="alert" style="margin:10px 0px 10px 0px;">
                Usuario não tem processo de doação iniciado nessa campanha, iniciar um agora clicando no botão abaixo.
                </div>';
                //Parte de criar nova doação aqui em baixo?>


                <?php
              }else{//Listar as doacoes não confirmadas do usuario
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped">';
                echo "<tr>
                <th>Data</th>
                <th>Confirmar Doação</th>
                </tr>";
                for ($i=0; $i < sizeof($doacoes); $i++) {
                  ?>
                  <tr>
                    <td> <?php echo $doacoes[$i]->getData(); ?></td>
                    <td>
                      <button id="doacao<?php echo $i+1;?>" name="doacao" class="btn btn-primary btn-block" value="<?php echo $doacoes[$i]->getIdDoacao(); ?>"> Selecionar <?php echo $i+1;?></button>
                    </td>
                  </tr>
                  <?php
                }
                echo'</table>';
                echo '</div>';
                ?>

                <div class="panel panel-default" id="SelecaoMateriais">
                      <?php
                                        $materiaisCampanha = AtendenteController::getInstance()->listarMateriaisCampanha($idCampanha);
                                        $materiais = AtendenteController::getInstance()->listarMateriais();

                                        $opcoes ="";
                                        for ($i=0; $i < sizeof($materiaisCampanha); $i++) {
                                          $opcoes .= '<option value="'.$materiaisCampanha[$i]->getCodigo().'">';
                                          $opcoes .= $materiaisCampanha[$i]->getNome();
                                          $opcoes .= '</option>';
                                        }
                                        ?>
                                        <h3 class="page-header" id="doacaoTitle"></h3>
                                        <form id="confirmarDoacao" method="post" action="rotas.php">
                                          <table id="lista" class="table table-striped">
                                            <thead>
                                              <tr>
                                                <th>Item</th>
                                                <th>Quantidade</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td><?php
                                                echo '<select name="material1">';
                                                echo $opcoes;
                                                echo "</select>";
                                                ?>
                                              </td>
                                              <td><input type="number" required name="quantidade1" size="10" /></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                        <!--Irá armazenar a quantidade de linhas-->
                                        <input type="hidden" id="quantidade_itens" value="1" name="quantidade_itens" />
                                        <input type="hidden" id="doacaoId" name="idDoacao"/>
                                        <input type="hidden"  name="cpfAtendente" value="<?php echo $_SESSION['cpf']; ?>"/>
                                        <input type="hidden" name="botaoConfirmarDoacao" value="">
                                      </form>



                                      <div class="col-md-6">
                                        <a  class="btn btn-primary btn-block" id="mais">Adicionar Item</a>
                                      </div>
                                      <div class="col-md-6">
                                        <a class="btn btn-primary btn-block" id="enviar">Confirmar Doação</a>
                                      </div>
                </div>

                <?php
              }
            }else{
              echo '<div class="alert alert-warning row alert-dismissible" role="alert" style="margin:10px 0px 10px 0px;">
              <strong>Desculpe!</strong> Usuario não encontrado. Tente efetuar um cadastro rápido no painel ao lado.
              </div>';
            }
            echo '</div>';
          } ?>

        </div>

        <?php
      }else{//Usuario Não atende na campanha
        ?>
        <div class="alert alert-warning row alert-dismissible" role="alert" style="margin:10px 0px 10px 0px;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Desculpe!</strong> Você não tem permissão de atender nesta campanha.
        </div>

        <?php

      }

    }else{
      header('location:atendente.php');
    }

    ?>
  </div>
</div>
</div>

<div class="container">

  <?php include("footer.php"); ?>
</div>
