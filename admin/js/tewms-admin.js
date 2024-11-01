(function ($) {
	'use strict';
	$(document).ready(function () {
		$('.tewms_fav_clr').select2({
			placeholder: select2Localization.placeholder,
			width: '80%',
			border: '1px solid #e4e5e7',
		});

		$('.tewms_fav_clr').on("select2:select", function (e) {
			var data = e.params.data.text;
			if (data == 'all') {
				$(".tewms_fav_clr > option").prop("selected", "selected");
				$(".tewms_fav_clr").trigger("change");
			}
		});
		$('.tewms_fav_clr2').select2({
			placeholder: select2Localization.placeholder,
			width: '80%',
			border: '1px solid #e4e5e7',
		});
		$('.tewms_fav_clr2').on("select2:select", function (e) {
			var data = e.params.data.text;
			if (data == 'all') {
				$(".tewms_fav_clr2 > option").prop("selected", "selected");
				$(".tewms_fav_clr2").trigger("change");
			}
		});
	});
})(jQuery);

