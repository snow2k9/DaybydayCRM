@extends('layouts.master')
@section('heading')
{{ __('All tasks')}}
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="project-board-lists">
            @foreach($statuses as $status)
            <div class="project-board-list">
                <header>{{ __($status->title)}}</header>
                <ul class="sortable" id="{{$status->title}}" data-status-external-id="{{$status->external_id}}" style="min-height: 32em;">
                    @foreach($tasks as $task)
                        <li data-task-id="{{$task->external_id}}">
                            @if($task->status_id == $status->id)
                                <div class="project-board-card-wrapper">
                                  <div class="project-board-card">
                                    <div class="position-relative">
                                        <strong>
                                        <a style="color: gray;" href="{{route('clients.show', $task->client->external_id)}}">
                                        {{$task->client->company_name}}
                                        </a>
                                        </strong>
                                    </div>
                                    <p class="project-board-card-title">
                                        <a href="{{route('tasks.show', $task->external_id)}}" class="link-color">
                                            {{$task->title}}
                                        </a>

                                    </p>
                                    <div class="project-board-card-description">
                                        {{ str_limit($task->description, 154, '...') }}
                                    </div>
                                  </div>
                                  <div class="project-board-card-footer">
                                    <ul class="list-inline" style="padding: 8px; min-height: 3.3em;">
                                        <li class="project-board-card-list">
                                            {{date(carbonFullDateWithText(), strtotime($task->created_at))}}
                                        </li>
                                        <li class="project-board-card-thumbnail text-right" style="float:right;">
                                        <a href="{{route('users.show', $task->user->external_id)}}" >
                                            <img src="{{$task->user->avatar}}" class="project-board-card-thumbnail-image" title="{{$task->user->name}}"/>
                                            <small>{{$task->user->name}}</small>
                                        </a>
                                        </li>
                                    </ul>
                                  </div>
                                </div>
                            @endif
                        @endforeach
                        </li>
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
$( ".sortable" ).sortable({
    axis: 'X',
    connectWith: '.sortable',
    receive: function (event, ui,) {
        var taskExternalId = ui.item.attr('data-task-id');
        var statusExternalId =  $(this).attr('data-status-external-id')
        var url = '{{ route("task.update.status", ":taskExternalId") }}';
        url = url.replace(':taskExternalId', taskExternalId);
    // POST to server using $.post or $.ajax
    $.ajax({
        data: {
            "_token": "{{ csrf_token() }}",
            "statusExternalId": statusExternalId,
            "test": $(this).parent().attr('id')
        },
        type: 'PATCH',
        url: url
    });
}
});
</script>

@endpush
