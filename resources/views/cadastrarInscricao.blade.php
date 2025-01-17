@extends('layouts.app')
@section('titulo','Inscrição')
@section('navbar')
    <!-- Home / Detalhes do Edital / Fazer Inscrição -->
    <li class="nav-item active">
      <a class="nav-link" style="color: black" href="{{ route('home') }}"
         onclick="event.preventDefault();
                       document.getElementById('VerEditais').submit();">
         {{ __('Home') }}
      </a>
      <form id="VerEditais" action="{{ route('home') }}" method="GET" style="display: none;">

      </form>
    </li>
    <li class="nav-item active">
      <a class="nav-link">/</a>
    </li>

    <li class="nav-item active">
      <a class="nav-link" href="detalhes" style="color: black" onclick="event.preventDefault(); document.getElementById('detalhesEdital').submit();" >
        {{ __('Detalhes do Edital')}}
      </a>
      @if(Auth::check())
        <form id="detalhesEdital" action="{{route('detalhesEdital')}}" method="GET" style="display: none;">
      @else
        <form id="detalhesEdital" action="{{route('detalhesEditalServidor')}}" method="GET" style="display: none;">
      @endif
          <input type="hidden" name="editalId" value="{{$editalId}}">
          <input type="hidden" name="mytime" value="{{$mytime}}">

        </form>
    </li>
    <li class="nav-item active">
      <a class="nav-link">/</a>
    </li>
    <li class="nav-item active">
      <a class="nav-link">Fazer Inscrição</a>
    </li>
@endsection
@section('content')

<style type="text/css">

@media screen and (max-width:576px) {
  div#divCidade{
    margin-top: 5%;
  }

  div#divUf{
    margin-top: 5%;
  }
}
</style>


<div class="container">
  <form id="formCadastro" autocomplete="off" method="POST" action="{{ route('cadastroInscricao') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="editalId" value="{{$editalId}}" />
    <input id="tipo" type="hidden" name="tipo" value=""/>
    <div class="row" style="margin-top:5%">
      <div class="card " style="width: 100%;">
          <div class="card-header">{{ __('Comprovante') }}</div>
          <div class="card-body">

            @if($comprovante == 'deferida')
              <div class="row justify-content-center" >
                <label for="comprovante" class="">{{ __('Comprovante: ') }}</label>

                <div class="col-sm-12">
                  Isento de pagamento
                  <input id="comprovante" type="hidden" name="comprovante" value="isento">
                </div>
              </div>
            @else
              <div class="row justify-content-center">

                <div class="col-sm-10">
                  <label for="comprovante" style="font-weight: bold">Selecione o comprovante gerado pelo pagamento da taxa do tipo de inscrição:</label>
                </div>

              </div>
              <div class="row justify-content-center">
                <div class="col-sm-10">
                  <div class="custom-file" style="width: 100%;">
                    <input disabled type="hidden" value="aux" id="comprovante">
                    <input id='elementoComprovante'  onclick="comprovanteSelecionado()"  type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="comprovante" value="{{ old('comprovante') }}">
                    <label style="">Este arquivo deve ser menor do que 2mb</label>
                    @error('comprovante')
                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>
              </div>
            @endif
          </div>
      </div>
    </div>
    <div  id="formulario" style="display: <?php if($comprovante != 'deferida') { echo('none'); }?> ;">
      <div class="row" style="margin-top: 5%">
        <div class="card" style="width: 100%;">
            <div class="card-header">{{ __('Inscrição') }}</div>
            <div class="card-body">

                <div class="row">
                  <label for="tipoInscricao" class="col-sm-4 col-form-label text-sm-right" >Tipo de Inscrição*:</label>
                  <div class="col-sm-8">
                    <input <?php if(old('tipo') == 'reintegracao')        {echo('checked');} ?> onclick="escolherTipo('reintegracao')" 			   type="radio" name="tipoInscricao" > Reintegração <br>
                    <input <?php if(old('tipo') == 'transferenciaInterna'){echo('checked');} ?> onclick="escolherTipo('transferenciaInterna')"  type="radio" name="tipoInscricao" > Transferencia Interna <br>
                    <input <?php if(old('tipo') == 'transferenciaExterna'){echo('checked');} ?> onclick="escolherTipo('transferenciaExterna')"  type="radio" name="tipoInscricao" > Transferencia Externa <br>
                    <input <?php if(old('tipo') == 'portadorDeDiploma')   {echo('checked');} ?> onclick="escolherTipo('portadorDeDiploma')" 		 type="radio" name="tipoInscricao" > Portador de Diploma <br>
                  </div>
                </div>


                <input disabled type="hidden" id="antigaOpcao" value="{{old('tipo')}}">

                <div id="historicoEscolar" class="form-group row" style="display: none" >      <!-- Arquivo historico escolar -->
                    <label for="Historico escolar" class="col-sm-4 col-form-label text-md-right">{{ __('Histórico escolar:') }}</label>


                    <div class="col-sm-6">
                      <div class="custom-file">
                        <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="historicoEscolar">
                        <label style="">Este arquivo deve ser menor do que 2mb</label>
                      </div>
                      @error('historicoEscolar')
                      <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                </div>

                <div id="declaracaoDeVinculo" class="form-group row" style="display: none">    <!-- Arquivo declaração de vinculo -->
                    <label for="Declaracao de Viculo" class="col-sm-4 col-form-label text-md-right">{{ __('Declaração de vínculo:') }}</label>

                    <div class="col-sm-6">
                      <div class="custom-file">
                        <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="declaracaoDeVinculo">
                        <label style="">Este arquivo deve ser menor do que 2mb</label>
                      </div>
                      @error('declaracaoDeVinculo')
                      <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                </div>

                <div id="programaDasDisciplinas" class="form-group row" style="display: none"> <!-- Arquivo programa das disciplinas -->
                    <label for="Programa das Disciplinas" class="col-sm-4 col-form-label text-md-right">{{ __('Programa das disciplinas: ') }}</label>

                    <div class="col-sm-6">
                      <div class="custom-file">
                        <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="programaDasDisciplinas" >
                        <label style="">Este arquivo deve ser menor do que 2mb</label>
                      </div>
                      @error('programaDasDisciplinas')
                      <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                </div>

                <div id="curriculo" class="form-group row" style="display: none">              <!-- Arquivo curriculo -->
                    <label for="Curriculo" class="col-sm-4 col-form-label text-md-right">{{ __('Perfil Curricular:') }}</label>

                    <div class="col-sm-6">
                      <div class="custom-file">
                        <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="curriculo" >
                        <label style="">Este arquivo deve ser menor do que 2mb</label>
                      </div>
                      @error('curriculo')
                      <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                  </div>
                    </div>

                <div id="enem" class="form-group row" style="display: none">                   <!-- Arquivo enenm -->
                    <label for="ENEM" class="col-sm-4 col-form-label text-md-right">{{ __('Nota no Exame Nacional do Ensino Médio (ENEM): ') }}</label>

                    <div class="col-sm-6">
                      <div class="custom-file">
                        <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="enem" >
                        <label style="">Este arquivo deve ser menor do que 2mb</label>
                      </div>
                      @error('enem')
                      <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                </div>

                <div class="form-group row">                                                   <!-- Curso -->
                    <label for="Curso" class="col-sm-4 col-form-label text-md-right">{{ __('Curso pretendido*:') }}</label>

                    <div class="col-sm-8" id="selectCurso">

                      <select class="form-control col-sm-10" name="curso" style="width: 100%">
                        <?php
                        foreach ($cursosDisponiveis as $curso) {
                          if($curso[0] != '#'){
                            if($curso[0] != ''){
                              echo('<option value=' . $curso[2] . '>' . $curso[0] . '</option>');
                            }
                          }
                        }
                        ?>
                      </select>
                    </div>
                </div>

                <div class="form-group row">                                                   <!-- Turno -->
                    <label for="Turno" class="col-sm-4 col-form-label text-md-right">{{ __('Turno*:') }}</label>

                    <div class="col-sm-8">
                      <select class="form-control col-sm-10" name="turno">
                        <option value="manhã">Manhã</option>
                        <option value="tarde">Tarde</option>
                        <option value="noite">Noite</option>
                        <option value="integral">Integral</option>
                        <option value="especial">Especial (EAD)</option>
                      </select>
                    </div>
                </div>

                <div class="form-group row justify-content-center">
                  <div class="col-sm-10">                                                   <!-- Polo -->
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="polo" class="field a-field a-field_a2 page__field" style="width: 100%">
                          <span class="a-field__label-wrap">
                            <span class="a-field__label">Polo (apenas aluno EAD):</span>
                          </span>
                        </label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 autocomplete">
                          <input id="polo" type="text" name="polo" autofocus class="form-control @error('polo') is-invalid @enderror field__input a-field__input" placeholder="Polo (apenas aluno EAD):" style="width: 100%;" value="{{ old('polo') }}" style="width:100%">

                      </div>
                    </div>

                    @error('polo')
                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>

            </div>
        </div>
      </div>
      <div class="row" style="margin-top:5%">
        <div class="card" style=" width: 100%">
            <div class="card-header">{{ __('Dados da IES') }}</div>
            <div class="card-body">
              <div class="card-body">
                <div class="form-group row">                                                   <!-- Curso de origem -->


                  <label for="cursoDeOrigem" class="field a-field a-field_a2 page__field" style="width: 100%">
                    <span class="a-field__label-wrap">
                      <span class="a-field__label">Curso de Origem*:</span>
                    </span>
                  </label>
                    <input id="cursoDeOrigem" type="text" name="cursoDeOrigem" class="form-control @error('cursoDeOrigem') is-invalid @enderror field__input a-field__input" placeholder="Curso de Origem*:" style="width: 100%;" value="{{ old('cursoDeOrigem') }}">
                  @error('cursoDeOrigem')
                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="form-group row">                                                   <!-- Instituição de origem -->
                  <label for="instituicaoDeOrigem" class="field a-field a-field_a2 page__field" style="width: 100%">
                    <span class="a-field__label-wrap">
                      <span class="a-field__label">Instituição de Origem*:</span>
                    </span>
                  </label>
                    <input id="instituicaoDeOrigem" type="text" name="instituicaoDeOrigem" autofocus class="form-control @error('instituicaoDeOrigem') is-invalid @enderror field__input a-field__input" placeholder="Instituição de Origem*:" style="width: 100%;" value="{{ old('instituicaoDeOrigem') }}">
                  @error('instituicaoDeOrigem')
                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="form-group row">                                                   <!-- Natureza da IES -->
                  <label for="naturezaDaIes" class="field a-field a-field_a2 page__field" style="width: 100%">
                    <span class="a-field__label-wrap">
                      <span class="a-field__label">Natureza da IES*:</span>
                    </span>
                  </label>
                    <input id="naturezaDaIes" type="text" name="naturezaDaIes" autofocus class="form-control @error('naturezaDaIes') is-invalid @enderror field__input a-field__input" placeholder="Natureza da IES*:" style="width: 100%;"  value="{{ old('naturezaDaIes') }}">
                  @error('naturezaDaIes')
                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="form-group row">
                  <div class="" style="">
                    <label for="endereco" class="field a-field a-field_a3 page__field" style="width: 100%;">
                      <span class="a-field__label-wrap">
                        <span class="a-field__label">CEP</span>
                      </span>
                    </label>
                      <input onblur="pesquisacep(this.value);" id="cep" type="text" name="cep" autofocus class="form-control field__input a-field__input" placeholder="CEP" maxlength="9" >
                  </div>
                </div>
                <div class="form-group row">  <!-- Endereço/Nº -->

                  <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-12">
                      <label for="endereco" class="field a-field a-field_a2 page__field" style="width: 100%">
                          <span class="a-field__label-wrap">
                            <span class="a-field__label">Rua*</span>
                          </span>
                      </label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                        <input id="rua" type="text" name="endereco" autofocus class="form-control @error('endereco') is-invalid @enderror field__input a-field__input" placeholder="Rua*" style="width: 100%;" value="{{ old('endereco') }}">
                    </div>
                  </div>

                    @error('endereco')
                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="col-sm-2">


                    <label for="num" class="field a-field a-field_a2 page__field" style="">
                        <span class="a-field__label-wrap">
                          <span class="a-field__label">Número*</span>
                        </span>
                    </label>
                        <input id="num" type="text" name="num" autofocus class="form-control @error('num') is-invalid @enderror field__input a-field__input" placeholder="Número*" style="width: 100%;" value="{{ old('num') }}">
                    @error('num')
                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">  <!-- Bairro/Cidade/Uf -->
                  <div class="col-sm-5" id="divBairro">
                    <label for="bairro" class="field a-field a-field_a2 page__field" style="width: 100%">
                        <span class="a-field__label-wrap">
                          <span class="a-field__label">Bairro*</span>
                        </span>
                    </label>
                        <input id="bairro" type="text" name="bairro" autofocus class="form-control @error('bairro') is-invalid @enderror field__input a-field__input" placeholder="Bairro*" style="width: 100%" value="{{ old('bairro') }}">
                    @error('bairro')
                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="col-sm-5" id="divCidade">
                    <label for="cidade" class="field a-field a-field_a2 page__field" style="width: 100%">
                        <span class="a-field__label-wrap">
                          <span class="a-field__label">Cidade*</span>
                        </span>
                    </label>
                        <input id="cidade" type="text" name="cidade" autofocus class="form-control @error('cidade') is-invalid @enderror field__input a-field__input" placeholder="Cidade*" style="width: 100%" value="{{ old('cidade') }}">
                    @error('cidade')
                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="col-sm-2" id="divUf">
                    <label for="uf" class="field a-field a-field_a2 page__field" style="width: 100%">
                        <span class="a-field__label-wrap">
                          <span class="a-field__label">UF*</span>
                        </span>
                    </label>
                        <input id="uf" type="text" name="uf" autofocus class="form-control @error('uf') is-invalid @enderror field__input a-field__input" placeholder="UF*" style="width: 100%" value="{{ old('uf') }}">
                    @error('uf')
                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>

              </div>
            </div>
        </div>
      </div>
    </div>
      <div class="row justify-content-center">
          <div class="">
            <button id="button" onclick="event.preventDefault();confirmar();" class="btn btn-primary btn-primary-lmts" style="margin-top:20px;">
              {{ __('Finalizar') }}
            </button>
          </div>
      </div>


    </div>
  </form>
</div>

<script type="text/javascript" >

  function escolherTipo(x) {
    if (x == "reintegracao") {
      document.getElementById("tipo").value = "reintegracao";
      document.getElementById("historicoEscolar").style.display = "";
      document.getElementById("declaracaoDeVinculo").style.display = "none";
      document.getElementById("enem").style.display = "none";
      document.getElementById("curriculo").style.display = "none";
      document.getElementById("programaDasDisciplinas").style.display = "none";

      if(document.getElementById("comprovante").value == 'isento'){
        document.getElementById("formulario").style.display = "";
      }

    }
    if (x == "transferenciaInterna") {
      document.getElementById("tipo").value = "transferenciaInterna";
      document.getElementById("historicoEscolar").style.display = "";
      document.getElementById("declaracaoDeVinculo").style.display = "";
      document.getElementById("enem").style.display = "none";
      document.getElementById("curriculo").style.display = "none";
      document.getElementById("programaDasDisciplinas").style.display = "none";

      if(document.getElementById("comprovante").value == 'isento'){
        document.getElementById("formulario").style.display = "";
      }
    }
    if (x == "transferenciaExterna") {
      document.getElementById("tipo").value = "transferenciaExterna";
      document.getElementById("historicoEscolar").style.display = "";
      document.getElementById("declaracaoDeVinculo").style.display = "";
      document.getElementById("enem").style.display = "none";
      document.getElementById("curriculo").style.display = "";
      document.getElementById("programaDasDisciplinas").style.display = "";

      if(document.getElementById("comprovante").value == 'isento'){
        document.getElementById("formulario").style.display = "";
      }
    }
    if (x == "portadorDeDiploma") {
      document.getElementById("tipo").value = "portadorDeDiploma";
      document.getElementById("historicoEscolar").style.display = "";
      document.getElementById("declaracaoDeVinculo").style.display = "";
      document.getElementById("enem").style.display = "";
      document.getElementById("curriculo").style.display = "none";
      document.getElementById("programaDasDisciplinas").style.display = "";

      if(document.getElementById("comprovante").value == 'isento'){
        document.getElementById("formulario").style.display = "";
      }
    }
  }

  var antigaOpcao = document.getElementById("antigaOpcao");
  if(antigaOpcao.value != null){
    escolherTipo(antigaOpcao.value);
  }

  function confirmar(){
    if(confirm("Tem certeza que deseja finalizar?") == true) {
      document.getElementById("formCadastro").submit();
   }
  }

  function comprovanteSelecionado(){
    document.getElementById("formulario").style.display = "";
  }


  //cep
  function limpa_formulário_cep() {
          //Limpa valores do formulário de cep.
          document.getElementById('rua').value=("");
          document.getElementById('bairro').value=("");
          document.getElementById('cidade').value=("");
          document.getElementById('uf').value=("");
  }

  function meu_callback(conteudo) {
      if (!("erro" in conteudo)) {
          //Atualiza os campos com os valores.
          document.getElementById('rua').value=(conteudo.logradouro);
          document.getElementById('bairro').value=(conteudo.bairro);
          document.getElementById('cidade').value=(conteudo.localidade);
          document.getElementById('uf').value=(conteudo.uf);

      } //end if.
      else {
          //CEP não Encontrado.
          limpa_formulário_cep();
          alert("CEP não encontrado.");
      }
  }

  function pesquisacep(valor) {

      //Nova variável "cep" somente com dígitos.
      var cep = valor.replace(/\D/g, '');

      //Verifica se campo cep possui valor informado.
      if (cep != "") {

          //Expressão regular para validar o CEP.
          var validacep = /^[0-9]{8}$/;

          //Valida o formato do CEP.
          if(validacep.test(cep)) {

              //Preenche os campos com "..." enquanto consulta webservice.
              document.getElementById('rua').value="...";
              document.getElementById('bairro').value="...";
              document.getElementById('cidade').value="...";
              document.getElementById('uf').value="...";


              //Cria um elemento javascript.
              var script = document.createElement('script');

              //Sincroniza com o callback.
              script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

              //Insere script no documento e carrega o conteúdo.
              document.body.appendChild(script);

          } //end if.
          else {
              //cep é inválido.
              limpa_formulário_cep();
              alert("Formato de CEP inválido.");
          }
      } //end if.
      else {
          //cep sem valor, limpa formulário.
          limpa_formulário_cep();
      }
  };

  var polos = ["Limoeiro","Carpina","Vitória da Conquista","Afrânio","Surubim","Gravatá","Ilhéus","Palmares","Pesqueira","Camaçari","Tabira","Trindade",]


  //autoCompĺete
  function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
          /*check if the item starts with the same letters as the text field value:*/
          if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            });
            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
        }
      }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
  }

  //end autocomplete

  autocomplete(document.getElementById("polo"), polos);

</script>


    @endsection
