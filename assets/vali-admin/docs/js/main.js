
(function () {
	"use strict";

	var treeviewMenu = $('.app-menu');

	// Toggle Sidebar
	$('[data-toggle="sidebar"]').click(function(event) {
		event.preventDefault();
		$('.app').toggleClass('sidenav-toggled');
	});

	// Activate sidebar treeview toggle
	$("[data-toggle='treeview']").click(function(event) {
		event.preventDefault();
		if(!$(this).parent().hasClass('is-expanded')) {
			treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
		}
		$(this).parent().toggleClass('is-expanded');
	});

	// Set initial active toggle
	$("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

	//Activate bootstrip tooltips
	$("[data-toggle='tooltip']").tooltip();

})();

$.fn.select2.defaults.set("theme", "bootstrap");

var dt = new Date();
var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
$.notifyDefaults({
  z_index: 9999999,
  allow_dismiss: true,
  newest_on_top: true,
  showProgressbar: false,
  placement: {
    from: "bottom",
    align: "right",
  },
  offset: 20,
  spacing: 10,
  delay: 5000,
  timer: 1000,
  url_target: "_blank",
  mouse_over: null,
  animate: {
    enter: "animated fadeInUpBig",
    exit: "animated fadeOutDownBig",
  },
  placement: {
    from: "top",
    align: "right",
  },
  onShow: null,
  onShown: null,
  onClose: null,
  onClosed: null,
  icon_type: "class",
  template:
    '<div class="notify-alert toast" role="alert">' +
    '<div class="toast-header">' +
    '<strong class="mr-auto">{1}</strong> <small>' +
    time +
    '</small><button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
    "</div>" +
    ' <div class="toast-body">{2}</div>' +
    "</div>",
});

$(document).ready(function () {
	window.addEventListener("keydown", function (e) {
		if (e.keyCode === 114 || (e.ctrlKey && e.keyCode === 70)) {
			if ($('#search').is(":focus")) {
				console.log("Default action of CtrlF")
				return true;
			} else {

				e.preventDefault();
				console.log("Search is not in focus");
				$('#search').focus();
			}
		}
	})
});

// $('input,select,button,textarea').keydown(function (e) {
// 	if (e.which === 38) {
// 		var index = $('input,select,textarea').index(this) - 1;
// 		$('input,select,textarea').eq(index).focus();
// 	}
// });
//
// $('body').on('keydown', 'input, select, button', function(e) {
//     var self = $(this)
//       , form = self.parents('form:eq(0)')
//       , focusable
//       , next
// 	  ;
//     if (e.keyCode == 13) {
//         focusable = form.find('input,select,textarea').filter(':visible');
//         next = focusable.eq(focusable.index(this)+1);
//         if (next.length) {
//             next.focus();
//         } else {
//             form.submit();
//         }
//         return false;
//     }
// });



$.mask.definitions['d'] = '[0-9]';
$.mask.definitions['9'] = '[A-Z,a-z]';

jQuery(function ($) {
	$(".noact").mask("dddd-dd-dd");
	$(".noBPKB").mask("9-dddddddd");
	$(".kode_transaksi").mask("dddd-dd-9999");
});

$(document).ready(function () {
	$.extend($.fn.autoNumeric.defaults, {
		aPad: false,
		aSep: '.',
		aDec: ',',
		aSign: ''
	});
});
jQuery(function ($) {
	$('.rupiah').autoNumeric('init').addClass('text-right');
});

function w3_open() {
	document.getElementById("myOverlay").style.display = "block";
}
function w3_close() {
	document.getElementById("myOverlay").style.display = "none";
}

$.extend(true, $.fn.dataTable.defaults, {
	'paging': true,
	'lengthChange': true,
	'searching': true,
	'info': true,

	"processing": true,
	"serverSide": true,

	"autoWidth": true,
	"scrollX": true,
});

$(".modal").modal({ backdrop: 'static', keyboard: false, show: false });
$(document).on('show.bs.modal', '.modal', function (event) {
	var zIndex = 1040 + (10 * $('.modal:visible').length);
	$(this).css('z-index', zIndex);
	setTimeout(function () {
		$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
	}, 0);
});

$(document).ready(function() {
	// Making 2 variable month and day
	var monthNames = [ "Januari", "Februari", "Maret", "April", "Mei", "Juni", "July", "Agustus", "September", "Oktober", "November", "Desember" ];
	var dayNames= ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"]

	// make single object
	var newDate = new Date();
	// make current time
	newDate.setDate(newDate.getDate());
	// setting date and time
	$('#Date').html(dayNames[newDate.getDay()] + ', ' + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear() + ' ');

	setInterval( function() {
	  // Create a newDate() object and extract the seconds of the current time on the visitor's
	  var seconds = new Date().getSeconds();
	  // Add a leading zero to seconds value
	  $("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
	  },1000);

	  setInterval( function() {
	  // Create a newDate() object and extract the minutes of the current time on the visitor's
	  var minutes = new Date().getMinutes();
	  // Add a leading zero to the minutes value
	  $("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
	  },1000);

	  setInterval( function() {
	  // Create a newDate() object and extract the hours of the current time on the visitor's
	  var hours = new Date().getHours();
	  // Add a leading zero to the hours value
	  $("#hours").html(( hours < 10 ? "0" : "" ) + hours);
	  }, 1000);
	});
