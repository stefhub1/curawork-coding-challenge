var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;
var commonCounter = [];
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
    } else if (mode === 'sent') {
      countArray[1] = response.content.pagination.total;
      $('#sentRequestsCount').html(countArray[1]);
    } else if (mode === 'received') {
      countArray[2] = response.content.pagination.total;
      $('#receivedRequestsCount').html(countArray[2]);
    } else if (mode === 'connections') {
      countArray[3] = response.content.pagination.total;
      $('#connectionsCount').html(countArray[3]);
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
      } else if (mode === 'sent') {
        tbodyHtml += '<button id="cancel_request_btn_' + trList[i].id + '" class="btn btn-danger me-1" onclick="deleteRequest(' + response.user + ', ' + trList[i].id + ')">' +
          'Withdraw Request' +
          '</button>';
      } else if (mode === 'received') {
        tbodyHtml += '<button id="cancel_request_btn_' + trList[i].id + '" class="btn btn-primary me-1" onclick="acceptRequest(' + response.user + ', ' + trList[i].id + ')">' +
          'Accept' +
          '</button>';
      } else if (mode === 'connections') {
        tbodyHtml += '<button style="width: 220px" id="get_connections_in_common_' + trList[i].connected_user_id + '" class="btn btn-primary me-1" type="button" ' +
          'data-bs-toggle="collapse" data-bs-target="#collapse_' + trList[i].connected_user_id + '" ' +
          'aria-expanded="false" ' +
          'aria-controls="collapseExample" ' + (trList[i].count === 0 ? "disabled" : "") + '>' +
          'Connections in common (' + trList[i].count + ')' +
          '</button>';
        tbodyHtml += '<button id="cancel_request_btn_' + trList[i].id + '" class="btn btn-danger" onclick="removeConnection(' + response.user + ', ' + trList[i].id + ')">' +
          'Remove Connection' +
          '</button>';
      }
      tbodyHtml += '</td>';
      tbodyHtml += '</tr>';
  
      if (mode === 'connections') {
        tbodyHtml += '<tr class="collapse" id="collapse_' + trList[i].connected_user_id + '">';
        tbodyHtml += '<td colspan="2">';
        tbodyHtml += '<div id="content_' + trList[i].connected_user_id + '" class="p-2 w-100">';
        tbodyHtml += '<table class="w-100"><tbody id="table-list-' + trList[i].connected_user_id + '"></tbody></table>';
        tbodyHtml += '<div class="d-flex justify-content-center w-100 py-2">';
        tbodyHtml += '<div class="w-100 px-2" id="connections_in_common_skeleton_' + trList[i].connected_user_id + '">';
        for (var c = 0; c < 5; c++) {
          tbodyHtml += '<div class="d-flex align-items-center mb-2 text-white bg-dark p-1 shadow" style="height: 45px">';
          tbodyHtml += '<strong class="ms-1 text-primary">Loading...</strong>';
          tbodyHtml += '<div class="spinner-border ms-auto text-primary me-4" role="status" aria-hidden="true"></div>';
          tbodyHtml += '</div>';
        }
        tbodyHtml += '</div>';
        tbodyHtml += '<button class="btn btn-sm btn-primary d-none" id="load_more_connections_in_common_' + trList[i].connected_user_id + '" ' +
          'onclick="getMoreConnectionsInCommon(' + response.user + ', ' + trList[i].connected_user_id + ', ' + i + ')">Load more</button>';
        tbodyHtml += '</div>';
        tbodyHtml += '</div>';
        tbodyHtml += '</td>';
        tbodyHtml += '</tr>';
      }
  
      $('#table-list').append(tbodyHtml);
      commonCounter[i] = 1;
  
      if (mode === 'connections') {
        var connectedID = trList[i].connected_user_id;
        var myCollapsible = document.getElementById('collapse_' + connectedID);
        myCollapsible.addEventListener('show.bs.collapse', function () {
          var splitAry = $(this).attr('id').split('_');
          var connectedID = splitAry[1];
          var rIndex = (($(this).get(0).rowIndex - 1) / 2);
          commonCounter[rIndex] = 1;
          getConnectionsInCommon(response.user, connectedID, 1);
        });
        myCollapsible.addEventListener('hidden.bs.collapse', function () {
          var splitAry = $(this).attr('id').split('_');
          var connectedID = splitAry[1];
          var rIndex = (($(this).get(0).rowIndex - 1) / 2);
          commonCounter[rIndex] = 1;
          $('#table-list-' + connectedID).html('');
        });
      }
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
  $('#load_more_btn_parent').addClass('d-none');
  toggleSkeleton(true);
  
  var params = '?page=' + skipCounter + '&takeAmount=' + takeAmount;
  var functionsOnSuccess = [
    [onSuccessResponse, ['connections', 'response']]
  ];
  
  ajax('/connections' + params, 'GET', functionsOnSuccess, null);
}

function getMoreConnections() {
  skipCounter++;
  
  getConnections();
}

function getConnectionsInCommon(userId, connectionId, counter) {
  $('#load_more_connections_in_common_' + connectionId).addClass('d-none');
  $('#connections_in_common_skeleton_' + connectionId).removeClass('d-none');
  
  var params = '?page=' + counter + '&takeAmount=' + takeAmount + '&connected_id=' + connectionId;
  var functionsOnSuccess = [
    [function (params, response) {
      if (response.status === 'success') {
        if (response.content.pagination.total > 10 && response.content.pagination.lastPage > response.content.pagination.currentPage) {
          $('#load_more_connections_in_common_' + connectionId).removeClass('d-none');
        } else {
          $('#load_more_connections_in_common_' + connectionId).addClass('d-none');
        }
  
        var trList = response.content.list;
        for (var i = 0; i < trList.length; i++) {
          var tbodyHtml = '<tr id="common-connections-row-' + trList[i].id + '">';
          tbodyHtml += '<td class="align-middle py-1" style="line-height: 30px;">';
          tbodyHtml += trList[i].name + ' - ' + trList[i].email;
          tbodyHtml += '</td>';
          tbodyHtml += '</tr>';
          
          $('#table-list-' + connectionId).append(tbodyHtml);
        }
  
        $('#connections_in_common_skeleton_' + connectionId).addClass('d-none');
      }
    }, ['common-connections', 'response']]
  ];
  
  ajax('/common-connections' + params, 'GET', functionsOnSuccess, null);
}

function getMoreConnectionsInCommon(userId, connectionId, i) {
  var ind = parseInt(i)
  commonCounter[ind]++;
  
  getConnectionsInCommon(userId, connectionId, commonCounter[ind]);
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
  
  ajax('/request-users/suggestions' + params, 'GET', functionsOnSuccess, null);
}

/**
 * Get more suggestions list
 */
function getMoreSuggestions() {
  skipCounter++;
  
  getSuggestions();
}

/**
 * Remove table row on success
 *
 * @param params
 * @param response
 */
function removeRow(params, response) {
  var modes = [
    'suggestions', 'sent', 'received', 'connections'
  ];
  
  var idsList = {
    suggestions: [
      '#suggestionsCount', '#sentRequestsCount'
    ],
    sent: [
      '#sentRequestsCount', '#suggestionsCount'
    ],
    received: [
      '#receivedRequestsCount', '#connectionsCount'
    ],
    connections: [
      '#connectionsCount', '#suggestionsCount'
    ]
  }
  
  if (response.status === 'success') {
    // remove suggestions row
    $('#request-row-' + params.id).remove();
    
    // update suggestions count
    if (countArray[modes.indexOf(params.mode)] > 0) {
      countArray[modes.indexOf(params.mode)] -= 1;
    }
    $(idsList[params.mode][0]).html(countArray[modes.indexOf(params.mode)]);
    
    // update sent request count
    var srCount = parseInt($(idsList[params.mode][1]).html());
    srCount += 1;
    $(idsList[params.mode][1]).html(srCount)
  }
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
    [removeRow, [{id: suggestionId, mode: 'suggestions'}, 'response']]
  ];
  
  ajax('/request-users', 'POST', functionsOnSuccess, ajaxForm(formItems));
}

function deleteRequest(userId, requestId) {
  var functionsOnSuccess = [[removeRow, [{id: requestId, mode: 'sent'}, 'response']]];
  
  ajax('/request-users/' + requestId, 'DELETE', functionsOnSuccess, null);
}

function acceptRequest(userId, requestId) {
  var functionsOnSuccess = [[removeRow, [{id: requestId, mode: 'received'}, 'response']]];
  
  ajax('/request-users/' + requestId, 'PUT', functionsOnSuccess, null);
}

function removeConnection(userId, connectionId) {
  var functionsOnSuccess = [[removeRow, [{id: connectionId, mode: 'connections'}, 'response']]];
  
  ajax('/connections/' + connectionId, 'DELETE', functionsOnSuccess, null);
}

$(function () {
  $('input:radio[name=btnradio]').on('click', function () {
    switch ($(this).attr('id')) {
      case 'connections':
        window.location.href = '/connections';
        break;
    
      case 'suggestions':
      case 'sent':
      case 'received':
        window.location.href = '/request-users/' + $(this).attr('id');
        break;
    }
  });
  
  skipCounter = 1;
  commonCounter = [];
  
  var checkedRadio = $('input:radio[name=btnradio]:checked');
  switch (checkedRadio.attr('id')) {
    case 'connections':
      getConnections();
      break;
    
    case 'suggestions':
      getSuggestions();
      break;
    case 'sent':
    case 'received':
      getRequests(checkedRadio.attr('id'))
      break;
  }
});