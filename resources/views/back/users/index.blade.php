@extends('back.template')

@section('head')

<style type="text/css">
  
  .badge {
    padding: 1px 8px 1px;
    background-color: #aaa !important;
  }

</style>

@stop

@section('main')

  {!!  HTML::backEntete(
  trans('back/users.dashboard') . link_to_route('user.create', trans('back/users.add'), [], ['class' => 'btn btn-info pull-right']),
  'user',
  trans('back/users.users')
  ) !!}

  <div id="tri" class="btn-group btn-group-sm">
    <a href="#" type="button" name="total" class="btn btn-default active">{{ trans('back/users.all') }} <span class="badge">{{  $counts['total'] }}</span></a>
    @foreach ($roles as $role)
      <a href="#" type="button" name="{!! $role->slug !!}" class="btn btn-default">{{ $role->titre . 's' }} <span class="badge">{{ $counts[$role->slug] }}</span></a>
    @endforeach
  </div>

	@if(Session::has('ok'))
    {!! HTML::alert('success', Session::get('ok')) !!}
	@endif

  <div class="pull-right link">{!! $links !!}</div>

	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ trans('back/users.name') }}</th>
					<th>{{ trans('back/users.role') }}</th>
					<th>{{ trans('back/users.seen') }}</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			  @include('back.users.table')
  		</tbody>
		</table>
	</div>

	<div class="pull-right link">{!! $links !!}</div>

@stop

@section('scripts')

  <script>
    
    $(function() {

      // Traitement vu
      $(document).on('change', ':checkbox', function() {    
        $(this).parents('tr').toggleClass('warning');
        $(this).hide().parent().append('<i class="fa fa-refresh fa-spin"></i>');
        var token = $('input[name="_token"]').val();
        $.ajax({
          url: 'uservu/' + this.value,
          type: 'PUT',
          data: "vu=" + this.checked + "&_token=" + token
        })
        .done(function() {
          $('.fa-spin').remove();
          $('input[type="checkbox"]:hidden').show();
        })
        .fail(function() {
          $('.fa-spin').remove();
          var chk = $('input[type="checkbox"]:hidden');
          chk.show().prop('checked', chk.is(':checked') ? null:'checked').parents('tr').toggleClass('warning');
          alert('{{ trans('back/users.fail') }}');
        });
      });

      // Traitement tri
      $('#tri').find('a').click(function(e) {
        e.preventDefault();
        // Icone d'attente
        $('.breadcrumb li').append('<span id="tempo" class="fa fa-refresh fa-spin"></span>');  
        // Aspect boutons
        $('#tri').find('a').removeClass('active');
        // Envoi ajax
        $.ajax({
          url: 'user/sort/' + $(this).attr('name'),
          type: 'GET',
          dataType: 'json'
        })
        .done(function(data) {
          $('tbody').html(data.view);
          $('.link').html(data.links);
          $('#tempo').remove();
        })
        .fail(function() {
          alert('{{ trans('back/users.fail') }}');
        });        
      });

    });

  </script>

@stop