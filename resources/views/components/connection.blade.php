<div class="my-2 shadow text-white bg-dark p-1" id="">
  <div class="d-flex justify-content-between">
    <table class="ms-1">
      <tbody id="table-list"></tbody>
    </table>
    <div>
      <button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button"
        data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" aria-controls="collapseExample">
        Connections in common ()
      </button>
      <button id="create_request_btn_" class="btn btn-danger me-1">Remove Connection</button>
    </div>

  </div>
  <div class="collapse" id="collapse_">

    <div id="content_" class="p-2">
      {{-- Display data here --}}
      <x-connection_in_common />

      <div id="connections_in_common_skeleton" class="{{-- d-none --}}">
        <br>
        <div class="px-2">
          @for ($i = 0; $i < 10; $i++)
            <x-skeleton />
          @endfor
        </div>
      </div>

      <div class="d-flex justify-content-center w-100 py-2">
        <button class="btn btn-sm btn-primary" id="load_more_connections_in_common_">
          Load more</button>
      </div>
    </div>
  </div>
</div>
