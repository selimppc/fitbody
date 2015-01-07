var AdminNotification = {
	showError: function (msg) {
		$.sticky(msg, {autoclose : 5000, position: "top-right", type: "st-error" });
	},
	showInfo: function (msg) {
		$.sticky(msg, {autoclose : 5000, position: "top-right", type: "st-info" });
	},
	showSuccess: function (msg) {
		$.sticky(msg, {autoclose : 5000, position: "top-right", type: "st-success" });
	}
};