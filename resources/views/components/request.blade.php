<div class="my-2 shadow text-white bg-dark p-1" id="">
  <table class="w-100">
    <tbody id="table-list"></tbody>
  </table>
  <div class="d-flex justify-content-center mt-2 py-3 d-none" id="load_more_btn_parent">
    @if ($mode == 'sent')
      <button class="btn btn-primary" onclick="getMoreRequests('sent')" id="load_more_btn">Load more</button>
    @else
      <button class="btn btn-primary" onclick="getMoreRequests('received')" id="load_more_btn">Load more</button>
    @endif
  </div>
</div>
