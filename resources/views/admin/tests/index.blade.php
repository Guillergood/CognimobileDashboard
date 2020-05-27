
@foreach($tests as $test)
   <p>{{ Request::fullUrl() }}/{{$test->id}}</p>
@endforeach
