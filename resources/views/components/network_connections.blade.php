<div class="row justify-content-center mt-5">
  <div class="col-12">
    <div class="card shadow  text-white bg-dark">
      <div class="card-header">Coding Challenge - Network connections</div>
      <div class="card-body">
        <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
          <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" {{ $tab == 'btnradio1' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary" for="btnradio1" id="get_suggestions_btn">
            Suggestions (<span id="suggestionsCount">{{ $suggestionscount }}</span>)
          </label>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" {{ $tab == 'btnradio2' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary" for="btnradio2" id="get_sent_requests_btn">
            Sent Requests (<span id="sentRequestsCount">{{ auth()->user()->requestUsers->count() }}</span>)
          </label>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off" {{ $tab == 'btnradio3' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary" for="btnradio3" id="get_received_requests_btn">
            Received Requests(<span id="receivedRequestsCount">{{ auth()->user()->receivedRequests->count() }}</span>)
          </label>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off" {{ $tab == 'btnradio4' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary" for="btnradio4" id="get_connections_btn">
            Connections (<span id="connectionsCount">{{ auth()->user()->connectedUsers->count() }}</span>)
          </label>
        </div>

        <div id="content">

          @if($tab == 'btnradio1')
            <x-suggestion/>
          @elseif($tab == 'btnradio2')
            <x-request :mode="'sent'"/>
          @elseif($tab == 'btnradio3')
            <x-request :mode="'received'"/>
          @elseif($tab == 'btnradio4')
            <x-connection/>
          @endif

          <div id="skeleton" class="d-none">
            @for ($i = 0; $i < 10; $i++)
              <x-skeleton/>
            @endfor
          </div>
        </div>
      </div>
    </div>
  </div>
</div>