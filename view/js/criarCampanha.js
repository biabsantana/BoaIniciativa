 (function($){
  butaoMaisMaterial = function(){
    var pai = document.getElementById('pai');
    var filho = document.getElementById('filho');
    var clone = filho.cloneNode(true);

    pai.appendChild(clone); 

  };

  butaoMaisDataMaterial = function(){
    var pai = document.getElementById('pai1');
    var filho = document.getElementById('filho1');
    var clone = filho.cloneNode(true);

    pai.appendChild(clone);

  };

  cadastrarMaterial = function(){
    var pai = document.getElementById('cadastro');
    var cadastro = document.getElementById('cadastroEscondido');
    cadastro.style.display = 'block';
    var clone = cadastro.cloneNode(true);

    pai.appendChild(clone);  
  };

  cadastrarMaterialData = function(){
    var pai = document.getElementById('cadastroData');
    var cadastro = document.getElementById('cadastroEscondido');
    cadastro.style.display = 'block';
    var clone = cadastro.cloneNode(true);

    pai.appendChild(clone);  
  };

  removerCadastro = function(me){
    var remove = $(me).closest('#cadastroEscondido');

    remove.fadeOut(400, function(){
        remove.remove();
    });

    return false;
  };

  removeCadastroData = function(me){
    var remove = $(me).closest('#cadastroEscondidoData');

    remove.fadeOut(400, function(){
        remove.remove();
    });

    return false;
  };

})(jQuery);



