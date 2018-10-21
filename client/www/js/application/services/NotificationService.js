(function () {
    "use strict";

    /**
     * @ngdoc service
     * @name ozelden.services.NotificationService
     *
     * @description This service used to show notification messages on the page.
     */
    function NotificationService($mdToast) {
        var self = this;

        /**
         * @ngdoc method
         * @name ozelden.services.NotificationService#showMessage
         * @methodOf ozelden.services.NotificationService
         *
         * @description Shows a toast on the top right of the screen with the
         *              given message.
         * @param {String} msg - message string to show in the toast.
         */
        function showMessage(msg) {
            if (!!msg && typeof msg === 'string') {
                var temp = "<md-toast><span style='font-weight:400; font-size: 12pt;' flex>"
                    + msg + "</span></md-toast>";
                $mdToast.show({
                    template: temp,
                    hideDelay: 6000,
                    position: 'bottom left'
                });
            }
        }

        function toaster(msg, type, elementId, inLeft) {
            // clear old toastr..
            toastr.remove();
            // set toastr options..
            toastr.options = {
                timeOut: 0,
                extendedTimeOut: 1000,
                positionClass: "toast-top-right",
                closeButton: true,
            };
            // toast new message..
            var notify = null;
            if (type === 'success') {
                notify = toastr.success(msg);
            } else if (type === 'warning') {
                notify = toastr.warning(msg);
            } else if (type === 'info') {
                notify = toastr.info(msg);
            }
            // arrange position..
            if (elementId) {
                var element = document.querySelector(elementId);
                var left = $(elementId).offset().left;
                var top = $(elementId).offset().top;
                var $notifyContainer = jQuery(notify).closest(
                    '.toast-top-right');
                if ($notifyContainer) {
                    if (inLeft) {
                        $notifyContainer.css("top", (top + 20) + 'px');
                        $notifyContainer.css("left", (left - 500) + 'px');
                    } else {
                        $notifyContainer.css("top", (top - 10) + 'px');
                        $notifyContainer.css("left", (left + 50) + 'px');
                    }
                }
            }
            return toastr;
        }

        this.showMessage = showMessage;
        this.toaster = toaster;

        return this;
    }

    angular.module('ozelden.services').factory('NotificationService', NotificationService);
}());