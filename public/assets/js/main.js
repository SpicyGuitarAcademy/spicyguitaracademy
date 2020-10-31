// Custom js codes

// preload resources
addEventListener("DOMContentLoaded", function() {
   // add the status bar
   let status = 
   `<div id="status" style="z-index: 99999999; display: none;" class="text-sm fixed-bottom mb-0 alert w-100 h-auto rounded-0">
      <i type='button' class='close' onclick="$('#status').fadeOut();">&times;</i>
      <i id="status-icon" class=""></i>
      <span id="status-msg"></span>
   </div>`;

   $("body").append(status);

   // add the confirm modal
   let confirm = 
   `<div id="confirm-modal" class="modal" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title" id="confirm-title"></h4></div>
            <div class="modal-body">
               <div id="confirm-msg"></div><br>
               <div>
                  <button id="confirm-ok" onclick="$('#confirm-modal').hide();" class="btn btn-sm btn-success">Okay</button>
                  <button id="confirm-cancel" onclick="$('#confirm-modal').hide();" class="btn btn-sm btn-danger">Cancel</button>
               </div>
            </div>
         </div>
      </div>
   </div>`;

   $("body").append(confirm);
});

async function request(method, url, body) {
   showLoading();
   try {
      let resp = await fetch(url, {method: method, headers: new Headers(), body: body});
      $("#status").fadeOut();
      if (resp.status == 200) {
         return resp.json();
      } else {
         showError(url + ": " + resp.statusText);
      }
   } catch (error) {
      showError(url + ": " + error);
   }
}

function confirmOperation(title, msg, okaycallback, cancelcallback) {
   $("#confirm-title").html(title);
   $("#confirm-msg").html(msg);
   $("#confirm-modal").show();
   $("#confirm-ok").off("click");
   $("#confirm-ok").on("click", okaycallback);
   $("#confirm-cancel").on("click", cancelcallback);
}

showSuccess = (msg, delay = true) => display('alert-success', 'fa fa-check text-success', msg, delay);
showError = (msg, delay = true) => display('alert-danger', 'fa fa-warning text-danger', msg, delay);
showInfo = (msg, delay = true) => display('alert-info', 'fa fa-info text-info', msg, delay);
showLoading = (msg = "Loading...", delay = true) => display('alert-info', 'fa fa-spinner fa-pulse text-info', msg, delay);

function display(attr, icon, msg, delay) {
   $('#status').removeClass('alert-info');
   $('#status').removeClass('alert-danger');
   $('#status').removeClass('alert-success');
   $('#status').addClass(attr);
   $('#status-icon')[0].className = icon;
   $('#status-msg').html(msg);
   $('#status').fadeIn();
   if (delay == false) {
      setTimeout(() => {
         $('#status').fadeOut();
      }, 5000);
   }
}