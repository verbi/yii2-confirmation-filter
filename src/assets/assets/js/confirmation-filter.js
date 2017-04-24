function initConfirmationFilter() {
    var handler = function (event) {
        var $this = $(window.yii),
                method = $this.data('method'),
                message = $this.data('confirm'),
                form = $this.data('form'),
                pub = window.yii;
        if (method === undefined && message === undefined && form === undefined) {
            return true;
        }

        if (message !== undefined) {
            $.proxy(pub.confirm, this)(message, function () {
                pub.handleAction($this, event);
            });
        } else {
            pub.handleAction($this, event);
        }
        event.stopImmediatePropagation();
        return false;
    };

    // handle data-confirm and data-method for clickable and changeable elements
    $(document).on('click.yii', pub.clickableSelector, handler)
            .on('change.yii', pub.changeableSelector, handler);
}