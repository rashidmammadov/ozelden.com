(function () {
    'use strict';

    function UserChildrenCtrl() {
        var self = this;

        this.childList = [{
            childId: 1,
            name: 'Rashid',
            surname: 'Mammadov',
            classes: [{
                className: 'my first class'
            },{
                className: 'my second class'
            },{
                className: 'my third class'
            }]
        },{
            childId: 1,
            name: 'Rashid',
            surname: 'Mammadov'
        }]
    }

    angular.module('ozelden.controllers').controller('UserChildrenCtrl', UserChildrenCtrl);
}());