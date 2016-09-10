  <div class="box box-default">
    <div class="box-header">
       Documentos
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body">
      <ul class="nav nav-stacked">
      @forelse ($documentos as $doc)
        <li><a href="{{ $doc->url() }}">{{$doc->nombre}} <span class="pull-right badge bg-blue"><i class="fa fa-download"></i></span></a></li>
      @empty
         <li><a>No Hay Documentos</a></li>
      @endforelse
      </ul>
      
    </div>
  </div>
