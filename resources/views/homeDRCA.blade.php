@extends('layouts.app')

@section('content')

<style type="text/css">

.hover_img a { position:relative; }
.hover_img a span { position:absolute; display:none; z-index:99; }
.hover_img a:hover span { display:block; height: 100px; width: 300px; overflow: visible;}

</style>

<div class="container" style="width: 100rem; margin-left: -50px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="width: 100rem;">
                <div class="card-header">Editais</div>

                <div class="card-body">
                  <table class="table table-ordered table-hover">

                    @foreach ($editais as $edital)
                    <tr>
                      <td  style="width: 45rem"> <!-- time line -->
                        <div class="hover_img">
                             <a >
                               <?php
                                 $nomeEdital = explode(".pdf", $edital->nome);
                                 echo ($nomeEdital[0]);
                                ?>
                               <span>
                                 <img src="<?php
                                  if($edital->inicioIsencao > $mytime){
                                    echo (asset('images/timeline1.png'));
                                  }

                                  if($edital->inicioIsencao <= $mytime){
                                    if($edital->fimIsencao >= $mytime){
                                      echo (asset('images/timeline2.png'));
                                    }
                                  }

                                  if($edital->inicioRecursoIsencao <= $mytime){
                                    if($edital->fimRecursoIsencao >= $mytime){
                                      echo (asset('images/timeline3.png'));
                                    }
                                  }

                                  if($edital->inicioInscricoes <= $mytime){
                                    if($edital->fimInscricoes >= $mytime){
                                      echo (asset('images/timeline4.png'));
                                    }
                                  }
                                  if($edital->inicioRecurso <= $mytime){
                                    if($edital->fimRecurso >= $mytime){
                                      echo (asset('images/timeline5.png'));
                                    }
                                  }
                                  if($edital->fimRecurso <= $mytime){
                                    echo (asset('images/timeline6.png'));
                                  }


                                 ?>" alt="image" height="140"/>
                               </span>
                             </a>

                         </div>
                      </td>
                      <td>
                        <form method="GET" action="{{route('editalEscolhido')}}">
                            @csrf
                            <input type="hidden" name="editalId" value="{{$edital->id}}">
                            <input type="hidden" name="tipo" value="homologarInscricoesReintegracao">

                            @if($edital->inicioInscricoes<= $mytime)
                              @if($edital->fimInscricoes >= $mytime)
                                <button type="submit" class="btn btn-primary btn-primary-lmts" >
                                    {{ __('Homologar Inscrições Reintegração') }}
                                </button>
                              @else
                              <button type="submit" disabled class="btn btn-primary btn-primary-lmts"  >
                                  {{ __('Homologar Inscrições Reintegração') }}
                              </button>
                              @endif
                            @else
                            <button type="submit" disabled class="btn btn-primary btn-primary-lmts"  >
                                {{ __('Homologar Inscrições Reintegração') }}
                            </button>
                            @endif
                        </form>
                      </td>
                      <td>
                      <a href="{{ route('download', ['file' => $edital->pdfEdital])}}" target="_new">Download</a>
                      </td>
                    </tr>
                    @endforeach
                  <!-- Exemplo de botão danger dividido -->
                {{ $editais->links() }}
            </div>
        </div>
    </div>
</div>

@if(session()->has('jsAlert'))
    <script>
        alert('{{ session()->get('jsAlert') }}');
    </script>
@endif


@endsection