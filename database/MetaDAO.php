<?php
require_once('MaterialDAO.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/BoaIniciativaV3/"."model/Meta.php");
require_once("Sql.php");

/**
*Classe MetaDAO
* Classe referente a manipulação de uma meta no banco de dados
*/
class MetaDAO
{
  private static $instance;

  public function __construct(){

  }

  public static function getInstance() {
    if (!isset(self::$instance))
    self::$instance = new MetaDAO();
    return self::$instance;
  }

  /** Método responsável pela personificação das informações da tabela meta como um objeto php
  * @param $row array que contém as informações de uma linha onde as chaves são os nomes das colunas e os valores são referentes ao valor da coluna daquela linha
  * @return objeto do tipo Administrador
  */
  public function popularMeta($linha){
    $meta = new Meta($linha['idcampanha'], $linha['codmaterial'], $linha['quantidade']);
    return $meta;
  }

  /** Método responsável por adicionar uma meta
  * @param $meta informações da meta a ser adicionada
  * @return retorna um booleano informando se a meta foi adicionada ou não
  */
  public function adicionarMeta($meta){//Colocar para passar model Meta

    try{
      $sql = Sql::getInstance()->adicionarMetaSQL();
      $stmt = ConexaoDB::getConexaoPDO()->prepare($sql);
      $stmt->bindParam(1, $meta->getCodMaterial());
      $stmt->bindParam(2, $meta->getIdCampanha());
      $stmt->bindParam(3, $meta->getQuantidade());

      // Talvez não precise verificar
      $validar = ConexaoDB::getConexaoPDO()->prepare(Sql::getInstance()->validadeAdicionarMetaSQL());
      $validar->bindParam(1,$meta->getCodMaterial());
      $validar->bindParam(2,$meta->getIdCampanha());
      $validar->execute();

      if($validar->errorCode() != "00000"){
        echo "Erro ao verificar existencia da Meta no Banco. Codigo Erro: ". $stmt->errorCode(). ":";
        var_dump($validar->errorInfo());
        return false;
      }

      if($validar->rowCount()==0){
        $stmt->execute();
        if($stmt->errorCode() != "00000"){
          echo "Erro ao Adicionar a Meta no Banco. Codigo Erro: ". $stmt->errorCode(). ":";
          var_dump($stmt->errorInfo());
          return false;
        }
        return true;
      }
      else {
        echo "<br> Esta meta ja existe!<br>";
        return false;
      }

    }catch(Exception $e){

    }
  }

  /** Método responsável por buscar uma meta de determinada campanha
  * @param $idCampanha id da campanha ao qual as metas devem ser buscadas
  * @return retorna uma lista de metas
  */
  public function buscarMetasCampanha($idCampanha){
    try{
      $sql = Sql::getInstance()->buscarmetasCampanhaSQL();
      $stmt = ConexaoDB::getConexaoPDO()->prepare($sql);
      $stmt->bindParam(1,$idCampanha);
      $stmt->execute();

      if($stmt->rowCount()==0){
        return null;
      }

      $arrayMetas = array();

      while($linha = $stmt->fetch(PDO::FETCH_ASSOC)){
        $meta = new Meta($linha['idcampanha'], $linha['codmaterial'], $linha['quantidade']);
        $arrayMetas[]=$meta;
      }

      return $arrayMetas;

    }catch (Exception $e){

    }

  }

  /** Método responsável por remover as metas de determinada campanha
  * @param $idCampanha id da campanha ao qual as metas devem ser removidas
  */
  public function removerMetasCampanha($idCampanha){
    try{
      $sql = Sql::getInstance()->removerMetaSQL();
      $p_sql = ConexaoDB::getConexaoPDO()->prepare($sql);
      $p_sql->bindParam(1, $idCampanha);
      $p_sql->execute();
    }catch (Exception $e){

    }
  }

}

?>
