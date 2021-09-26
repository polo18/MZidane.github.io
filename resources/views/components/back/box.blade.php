@props([
  'type',
  'number',
  'title',
  'route',
  'model',
])

<div class="col-lg-3 col-6">
  <div class="small-box bg-{{ $type }}">
    <div class="inner">
      
      <div class="d-flex justify-content-between">
        <h3>{{ $number }}</h3>
        
        <form action="{{ route('purge', $model) }}" method="POST" class="">
          @csrf
          @method('PUT')
          <button type="submit" class="btn btn-{{ $type }} btn-block text-warning">@lang('Vidé notif')</button>
        </form>

      </div>

      <p>@lang($title)</p>

    </div>
    <div class="icon">
      <i class="ion ion-bag"></i>
    </div>
    <a href="{{ route($route) }}" class="small-box-footer">
      @lang('More info') 
      <i class="fas fa-arrow-circle-right"></i>
    </a>
  </div>
</div>