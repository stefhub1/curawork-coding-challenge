<div class="my-2 shadow text-white bg-dark p-1" id="">
  <table class="w-100">
    <tbody id="table-list"></tbody>
  </table>
  <div class="d-flex justify-content-center mt-2 py-3 d-none" id="load_more_btn_parent">
    <button class="btn btn-primary" onclick="getMoreConnections()" id="load_more_btn">Load more</button>
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
