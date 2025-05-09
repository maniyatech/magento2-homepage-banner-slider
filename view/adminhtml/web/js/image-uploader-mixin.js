/**
 * ManiyaTech
 *
 * @author  Milan Maniya
 * @package ManiyaTech_HomepageBannerSlider
 */

define(
    [], function () {
        'use strict';

        return function (ImageUploader) {
            return ImageUploader.extend(
                {
                    getAllowedFileExtensionsInCommaDelimitedFormat: function () {
                        var allowedExtensions = window.allowedExtensions ? window.allowedExtensions.toUpperCase().split(',')
                        : this.allowedExtensions.toUpperCase().split(' ');

                        // if jpg and jpeg in allowed extensions, remove jpeg from list
                        if (allowedExtensions.indexOf('JPG') !== -1 && allowedExtensions.indexOf('JPEG') !== -1) {
                            allowedExtensions.splice(allowedExtensions.indexOf('JPEG'), 1);
                        }

                        return allowedExtensions.join(', ');
                    }
                }
            );
        };
    }
);
