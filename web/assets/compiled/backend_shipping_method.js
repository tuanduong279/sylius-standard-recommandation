(function ($) {
    'use strict';

    var methods = {
        init: function(options) {
            var settings = $.extend({
              'prototypePrefix': false,
              'containerSelector': false
            }, options);

            return this.each(function() {
                show($(this), false);
                $(this).change(function() {
                    show($(this), true);
                });

                function show(element, replace) {
                    var selectedValue = element.val();
                    var prototypePrefix = element.attr('id');
                    if (false != settings.prototypePrefix) {
                        prototypePrefix = settings.prototypePrefix;
                    }

                    var prototypeElement = $('#' + prototypePrefix + '_' + selectedValue);
                    var container;

                    if (settings.containerSelector) {
                        container = $(settings.containerSelector);
                    } else {
                        container = $(prototypeElement.data('container'));
                    }

                    if (!container.length) {
                        return;
                    }

                    if (!prototypeElement.length) {
                        container.empty();
                        return;
                    }

                    if (replace || !container.html().trim()) {
                        container.html(prototypeElement.data('prototype'));
                    }
                }
            });
        }
    };

    $.fn.handlePrototypes = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.handlePrototypes' );
        }
    };
})(jQuery);

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
(function ($) {
    'use strict';

    $(document).ready(function() {
        $('#sylius_shipping_method_calculator').handlePrototypes({
            'prototypePrefix': 'sylius_shipping_method_calculator_calculators',
            'containerSelector': '.configuration'
        });
    });
})( jQuery );
