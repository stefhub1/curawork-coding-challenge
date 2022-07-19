<div class="row justify-content-center mt-5">
  <div class="col-12">
    <div class="card shadow  text-white bg-dark">
      <div class="card-header">Coding Challenge - Network connections</div>
      <div class="card-body">
        <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
          <input type="radio" class="btn-check" name="btnradio" id="suggestions" autocomplete="off" {{ $tab == 'suggestions' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary" for="suggestions" id="get_suggestions_btn">
            Suggestions (<span id="suggestionsCount">{{ $suggestionscount }}</span>)
          </label>

          <input type="radio" class="btn-check" name="btnradio" id="sent" autocomplete="off" {{ $tab == 'sent' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary" for="sent" id="get_sent_requests_btn">
            Sent Requests (<span id="sentRequestsCount">{{ auth()->user()->requestUsers->count() }}</span>)
          </label>

          <input type="radio" class="btn-check" name="btnradio" id="received" autocomplete="off" {{ $tab == 'received' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary" for="received" id="get_received_requests_btn">
            Received Requests(<span id="receivedRequestsCount">{{ auth()->user()->receivedRequests->count() }}</span>)
          </label>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off" {{ $tab == 'btnradio4' ? 'checked' : '' }}>
          <label class="btn btn-outline-primary" for="btnradio4" id="get_connections_btn">
            Connections (<span id="connectionsCount">{{ auth()->user()->connectedUsers->count() }}</span>)
          </label>
        </div>

        <div id="content">

          @if($tab == 'suggestions')
            <x-suggestion/>
          @elseif($tab == 'sent')
            <x-request :mode="'sent'"/>
          @elseif($tab == 'received')
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