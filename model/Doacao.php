<?php
/**
* Classe que tem as informacoes de uma doacao
*/
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."database/DoacaoDAO.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."database/DoacaoMaterialDAO.php");

class Doacao
{

  private $idDoacao;
  private $data;
  private $confirmado;
  private $idCampanha;
  private $atendenteconfirma;
  private $cpfDoador;

  public function __construct($idDoacao, $cpfDoador, $confirmado, $data, $idCampanha)
  {
    $this->idDoacao = $idDoacao;
    $this->cpfDoador = $cpfDoador;
    $this->confirmado = $confirmado;
    $this->idCampanha = $idCampanha;
    $this->data = $data;
  }

  public function confirmarDoacao($atendenteconfirma){
    if(empty($this->atendenteconfirma)){
      $this->atendenteconfirma = $atendenteconfirma;
      $this->confirmado = true;
      return (new DoacaoDAO)->editarDoacao($this); //Conseguiu efetuar operação de associar atendente
    }
    return false;//Doacao já tee atendente associado
  }


  public function adicionarMaterial($materialDoado){
    array_push($itens, $materialDoado);
    (new DoacaoMaterialDAO)->adicionarMaterialDoacao($this, $materialDoado);
  }

  public function setItens($itens){
    $this->itens = $itens;

  }

  public function setAtendente($atendente){
    $this->atendenteconfirma = $atendente;
  }

  public function setIdDoacao($idDoacao){
    $this->idDoacao = $idDoacao;
  }
  public function getIdDoacao(){
    return $this->idDoacao;
  }
  public function getData(){
    return $this->data;
  }
  public function getConfirmado(){
    return $this->cofirmado;
  }
  public function getIdCampanha(){
    return $this->idCampanha;
  }
  public function getAtendente(){
    return $this->atendenteconfirma;
  }
  public function getItens(){
    return $this->itens;
  }
  public function getCpfDoador(){
    return $this->cpfDoador;
  }

}
?>
