<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."facade/AdministradorFacade.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."controller/AtendenteController.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."controller/DoadorController.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."facade/UsuarioFacade.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."database/UsuarioDAO.php");

if(isset($_POST['botaoLogar'])){
  session_start();
  if(isset($_POST['cpf']) && isset($_POST['senha'])){
    $cpf = $_POST['cpf'];
    $senha =  $_POST['senha'];




    $cpf = $_POST['cpf'];
    $senha =  $_POST['senha'];
    echo $cpf;
    echo $senha;

    $autenticado = UsuarioDAO::getInstance()->autenticarUsuariomd5($cpf, $senha);
    if ($autenticado){
      $_SESSION['cpf'] = $cpf;
      $_SESSION['senha'] = $senha;
      header('location:home.php');
    }else{
      unset($_SESSION['cpf']);
      unset($_SESSION['senha']);
      header('location:index.php');

    }
  }
}else if ( isset( $_POST['doarCampanha'] )) {//Apertou o botão de doar para Campanha no formulário
  if(isset($_POST['idCampanha']) && isset($_POST['cpfUsuario'])){
    $idCampanha= $_POST['idCampanha'];
    $cpfUsuario= $_POST['cpfUsuario'];//Substituir para verificação da sessão
    UsuarioFacade::getInstance()->efetuarDoacao($idCampanha,$cpfUsuario);
  }else{
    echo "Preencha todos os campos";
  }
}else if(isset($_POST['botaoCadastrar'])){
  if(isset($_SESSION['cpf']) && isset($_SESSION['senha'])){//Usuario já logado, mover para Home
    header('location:home.php');
  }else{ //Usuario deslogado, pode cadastrar
    if(isset($_POST['nome']) && isset($_POST['cpf']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['nascimento']) && isset($_POST['cep'])
    && isset($_POST['estado']) && isset($_POST['bairro']) && isset($_POST['cidade']) &&isset($_POST['longradouro']) && isset($_POST['numero']) && isset($_POST['complemento'])
    && isset($_POST['gender'])){
      $sexo = ($_POST['gender']=='male')? 'M': 'F';
      $nome = $_POST['nome'];
      $cpf = $_POST['cpf'];
      $email = $_POST['email'];
      $senha = $_POST['password'];
      $nascimento = $_POST['nascimento'];
      $cep = $_POST['cep'];
      $estado = $_POST['estado'];
      $bairro = $_POST['bairro'];
      $cidade = $_POST['cidade'];
      $longradouro = $_POST['longradouro'];
      $numero = $_POST['numero'];
      $complemento = $_POST['complemento'];
      echo "Tudo certo para cadastrar";
      $confirmacao = SistemaFacade::getInstance()->cadastrarNovoUsuario($nome,$cpf,$email,$senha,$nascimento,$cep,$estado,$bairro,$cidade,$longradouro,$numero,$complemento,$sexo);
      if($confirmacao){
        header('location:index.php');
        echo "Usuario Cadastrado com sucesso";
      }else{
        header('location:login.php');
        echo "Erro ao cadastrar";
      }
    }else{
      header('location:login.php');
      echo "Preencha todos os campos";
    }
    header('location:index.php');
  }
}else if(isset($_POST['botaoEditar'])){
  if(isset($_POST['cpf']) &&isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['sexo']) && isset($_POST['nascimento']) && isset($_POST['cep'])
  && isset($_POST['estado']) && isset($_POST['bairro']) && isset($_POST['cidade']) &&isset($_POST['logradouro']) && isset($_POST['numero']) && isset($_POST['complemento'])){

    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $sexo = ($_POST['sexo']=='male')? 'M': 'F';
    $nascimento = $_POST['nascimento'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];


    $confirmacao = UsuarioFacade::getInstance()->editarPerfil($cpf,$nome,$email,$sexo,$nascimento,$cep,$estado,$bairro,$cidade,$logradouro,$numero,$complemento);

    if($confirmacao){
      header('location:perfil.php');//funcionou
    }else{
      header('location:perfil.php');//nao funcionou
    }
  }else{
      header('location:perfil.php'); //falta informações
  }


}else if(isset($_POST['botaoConfirmarParticipacao'])){
  $cpf = $_POST['cpf'];
  $idCampanha = $_POST['idCampanha'];

  $confirmacao = AtendenteController::getInstance()->confirmarParticipacao($cpf,$idCampanha);


  if($confirmacao){
    header('location:campanhasAtendente.php');//funcionou
  }else{
    header('location:convites-atendente.php');//nao funcionou
  }
}else if(isset($_POST['botaoCancelarParticipacao'])){
  $cpf = $_POST['cpf'];
  $idCampanha = $_POST['idCampanha'];

  $confirmacao = AtendenteController::getInstance()->cancelarParticipacao($cpf,$idCampanha);


  if($confirmacao){
    header('location:convites-atendente.php');//funcionou
  }else{
    header('location:campanhasAtendente.php');//nao funcionou
  }
}else if(isset($_POST['botaoCadastroRapido'])){
  $cpf = $_POST['cpf'];
  $senha = $_POST['password'];
  $email = $_POST['email'];

  $confirmacao = AtendenteController::getInstance()->cadastroRapido($cpf,$email,$senha);
  if($confirmacao){
    header('location:cadastrorapido.php');//funcionou
  }else{
    header('location:cadastrorapido.php');//nao funcionou
  }
}else if(isset($_POST['botaoCancelarDoacao'])){

  $idDoacao = $_POST['idDoacao'];

   $confirmacao = DoadorController::getInstance()->cancelarDoacao($idDoacao);

   if($confirmacao){
     header('location:doador.php');//funcionou
   }else{
     header('location:doador.php');//nao funcionou
   }
}else if(isset($_POST['botaoEnviarDenuncia'])){
  $idCampanha = $_POST['idCampanha'];
  $motivo = $_POST['motivo'];
  $descricao = $_POST['descricao'];
  $cpf =$_POST['cpf'];
  $confirmacao = UsuarioController::getInstance()->enviarDenuncia($idCampanha, $motivo, $descricao, $cpf);

  header('location:doador.php');
}else if(isset($_POST['botaoConfirmarDoacao'])){

    $idDoacao = $_POST['idDoacao'];
    echo $idDoacao;

    $quantidade_itens = $_POST['quantidade_itens'];
    $materiaisDoados = array();
    for ( $x = 1; $x <= $quantidade_itens ; $x++ ){

        $item = $_POST["material$x"];
        $quantidade = $_POST["quantidade$x"];
        $materiaisDoados[$item]+=$quantidade;
    }
    unset($_POST['botaoConfirmarDoacao']);
    unset($_POST['quantidade_itens']);
    unset($_POST['idDoacao']);

    AtendenteController::getInstance()->receberMateriais($idDoacao, $materiaisDoados);

    header('location:atendente.php');


}else if(isset($_POST['botaoSenha'])){
session_start();
if(isset($_SESSION['cpf']) && isset($_SESSION['senha'])){//Usuario já logado, mover para Home
    $senhaAtual = $_SESSION['senha'];
    $senhaForm = $_POST['senha'];
    $novaSenha = $_POST['novasenha'];
    if($senhaAtual == $senhaForm){
      UsuarioFacade::getInstance()->editarSenha($novaSenha,$_SESSION['cpf']);
      header('location:perfil.php');//colocar confirmação na tela de alteração       
    }
    else{
      //colocar que senha está errada!
    }
}else{
    header('location:index.php');
}
}
?>
