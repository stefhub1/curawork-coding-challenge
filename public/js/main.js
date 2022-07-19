var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;
var countArray = [0, 0, 0, 0];

function toggleSkeleton(display) {
  var skeleton = $('#skeleton');
  if (display && skeleton.hasClass('d-none')) {
    skeleton.removeClass('d-none');
  } else {
    skeleton.addClass('d-none');
  }
}

function onSuccessResponse(mode, response) {
  if (response.status === 'success') {
    if (response.content.pagination.total > 10 && response.content.pagination.lastPage > response.content.pagination.currentPage) {
      $('#load_more_btn_parent').removeClass('d-none');
    } else {
      $('#load_more_btn_parent').addClass('d-none');
    }
    
    if (mode === 'suggestions') {
      countArray[0] = response.content.pagination.total;
      $('#suggestionsCount').html(countArray[0]);
    } else if (mode === 'btnradio2') {
      countArray[1] = response.content.pagination.total;
      $('#sentRequestsCount').html(countArray[1]);
    }
    
    // Inject table tbody data
    var trList = response.content.list;
    
    for (var i = 0; i < trList.length; i++) {
      var tbodyHtml = '<tr id="request-row-' + trList[i].id + '">';
      tbodyHtml += '<td class="align-middle py-1">';
      tbodyHtml += trList[i].name + ' - ' + trList[i].email;
      tbodyHtml += '</td>';
      tbodyHtml += '<td class="align-middle">';
      if (mode === 'suggestions') {
        tbodyHtml += '<button id="create_request_btn_' + trList[i].id + '" class="btn btn-primary me-1" onclick="sendRequest(' + response.user + ', ' + trList[i].id + ')">Connect</button>';
      } else if (mode === 'btnradio2') {
        tbodyHtml += '<button id="cancel_request_btn_' + trList[i].id + '" class="btn btn-danger me-1" onclick="deleteRequest(' + response.user + ', ' + trList[i].id + ')">' +
          'Withdraw Request' +
          '</button>';
      } else if (mode === 'btnradio3') {
        tbodyHtml += '<button id="cancel_request_btn_' + trList[i].id + '" class="btn btn-primary me-1" onclick="acceptRequest(' + response.user + ', ' + trList[i].id + ')">' +
          'Accept' +
          '</button>';
      }
      tbodyHtml += '</td>';
      tbodyHtml += '</tr>';
      
      $('#table-list').append(tbodyHtml);
    }
    
    toggleSkeleton(false);
  }
}

function getRequests(mode) {
  $('#load_more_btn_parent').addClass('d-none');
  toggleSkeleton(true);
  
  var params = '?page=' + skipCounter + '&takeAmount=' + takeAmount;
  var functionsOnSuccess = [
    [onSuccessResponse, [mode, 'response']]
  ];
  
  ajax('/request-users/' + mode + params, 'GET', functionsOnSuccess, null);
}

function getMoreRequests(mode) {
  skipCounter++;
  
  getRequests(mode);
}

function getConnections() {
  // your code here...
}

function getMoreConnections() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnectionsInCommon(userId, connectionId) {
  // your code here...
}

function getMoreConnectionsInCommon(userId, connectionId) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

/**
 * get suggestions list
 */
function getSuggestions() {
  $('#load_more_btn_parent').addClass('d-none');
  toggleSkeleton(true);
  
  var params = '?page=' + skipCounter + '&takeAmount=' + takeAmount;
  var functionsOnSuccess = [
    [onSuccessResponse, ['suggestions', 'response']]
  ];
  
  ajax('/request-users/btnradio1' + params, 'GET', functionsOnSuccess, null);
}

/**
 * Get more suggestions list
 */
function getMoreSuggestions() {
  skipCounter++;
  
  getSuggestions();
}

/**
 * Send connection request
 * @param userId
 * @param suggestionId
 */
function sendRequest(userId, suggestionId) {
  var formItems = [
    ['suggestionId', suggestionId]
  ];
  
  var functionsOnSuccess = [
    [function (mode, response) {
      if (response.status === 'success') {
        // remove suggestions row
        $('#request-row-' + suggestionId).remove();
        
        // update suggestions count
        if (countArray[0] > 0) {
          countArray[0] -= 1;
        }
        $('#suggestionsCount').html(countArray[0]);
        
        // update sent request count
        var srCount = parseInt($('#sentRequestsCount').html());
        srCount += 1;
        $('#sentRequestsCount').html(srCount)
      }
    }, ['suggestions', 'response']]
  ];
  
  ajax('/request-users', 'POST', functionsOnSuccess, ajaxForm(formItems));
}

function deleteRequest(userId, requestId) {
  var functionsOnSuccess = [
    [function (mode, response) {
      if (response.status === 'success') {
        // remove sent request row
        $('#request-row-' + requestId).remove();
        
        // update sent request count
        if (countArray[1] > 0) {
          countArray[1] -= 1;
        }
        $('#sentRequestsCount').html(countArray[1]);
        
        // update suggestions count
        var srCount = parseInt($('#suggestionsCount').html());
        srCount += 1;
        $('#suggestionsCount').html(srCount)
      }
    }, ['suggestions', 'response']]
  ];
  
  ajax('/request-users/' + requestId, 'DELETE', functionsOnSuccess, null);
}

function acceptRequest(userId, requestId) {
  // your code here...
}

function removeConnection(userId, connectionId) {
  // your code here...
}

$(function () {
  $('input:radio[name=btnradio]').on('click', function () {
    switch ($(this).attr('id')) {
      case 'btnradio4':
        window.location.href = '/home';
        break;
  
      case 'btnradio1':
      case 'btnradio2':
      case 'btnradio3':
        window.location.href = '/request-users/' + $(this).attr('id');
        break;
    }
  });
  
  skipCounter = 1;
  
  var checkedRadio = $('input:radio[name=btnradio]:checked');
  switch (checkedRadio.attr('id')) {
    case 'btnradio4':
      break;
    
    case 'btnradio1':
      getSuggestions();
      break;
    case 'btnradio2':
    case 'btnradio3':
      getRequests(checkedRadio.attr('id'))
      break;
  }
});