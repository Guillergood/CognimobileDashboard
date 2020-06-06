@extends('layouts.admin')
@section('content')
@foreach($tests as $test)
    <div class="content" id="{{ $test->id ?? '' }}">
        <div class="c-body">
            <div class="fade-in">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-0">ID: {{ $test->id ?? '' }}</h4>
                                <div class="small text-muted">Name: {{ $test->name ?? '' }}</div>
                                <button type="button" class="btn btn-danger" aria-expanded="false" onclick="deleteTest({{$test->id}})">
                                  <i class="fas fa-trash">

                                  </i>
                                  Delete Test
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
@section('scripts')
@parent
<script>

function deleteTest(id) {
      var message = 'Are you sure you want to delete the item?';
      if (confirm(message)) {
        let winName = 'DeleteTestWindow';

        // Find everything up to the first slash and save it in a backreference
        regexp = /(\w+:\/\/[^\/]+)\/.*/;

        // Replace the href with the backreference and the new uri
        let winURL = window.location.href.replace(regexp, "$1/admin/tests/delete");
        let windowoption='resizable=yes,height=600,width=800,location=0,menubar=0,scrollbars=1';
        let params = { 'id' : id, "_token": $("meta[name='csrf-token']").attr("content")};
        let form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", winURL);
        form.setAttribute("target",winName);
        for (let i in params) {
            if (params.hasOwnProperty(i)) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = i;
                input.value = params[i];
                form.appendChild(input);
            }
        }
        document.body.appendChild(form);
        window.open('', winName,windowoption);
        form.target = winName;
        form.submit();
        document.body.removeChild(form);

        let rowToDelete = document.getElementById(id);
        let parent = rowToDelete.parentElement;
        parent.removeChild(rowToDelete);
      }

}


</script>
@endsection
