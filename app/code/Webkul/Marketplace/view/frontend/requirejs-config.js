/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   OrionAlliance_NewModule
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
var config = {
    map: {
        '*': {
            colorpicker: 'OrionAlliance_NewModule/js/colorpicker',
            verifySellerShop: 'OrionAlliance_NewModule/js/account/verify-seller-shop',
            editSellerProfile: 'OrionAlliance_NewModule/js/account/edit-seller-profile',
            sellerDashboard: 'OrionAlliance_NewModule/js/account/seller-dashboard',
            sellerAddProduct: 'OrionAlliance_NewModule/js/product/seller-add-product',
            sellerEditProduct: 'OrionAlliance_NewModule/js/product/seller-edit-product',
            sellerCreateConfigurable: 'OrionAlliance_NewModule/js/product/attribute/create',
            sellerProductList: 'OrionAlliance_NewModule/js/product/seller-product-list',
            sellerOrderHistory: 'OrionAlliance_NewModule/js/order/history',
            sellerOrderShipment: 'OrionAlliance_NewModule/js/order/shipment',
            colorPickerFunction: 'OrionAlliance_NewModule/js/color-picker-function',
            productGallery:     'OrionAlliance_NewModule/js/product-gallery',
            baseImage:          'OrionAlliance_NewModule/catalog/base-image-uploader',
            newVideoDialog:  'OrionAlliance_NewModule/js/new-video-dialog',
            openVideoModal:  'OrionAlliance_NewModule/js/video-modal',
            productAttributes:  'OrionAlliance_NewModule/catalog/product-attributes',
            configurableAttribute:  'OrionAlliance_NewModule/catalog/product/attribute',
            relatedProduct: 'OrionAlliance_NewModule/js/product/related-product',
            upsellProduct: 'OrionAlliance_NewModule/js/product/upsell-product',
            crosssellProduct: 'OrionAlliance_NewModule/js/product/crosssell-product',
            notification : 'OrionAlliance_NewModule/js/notification',
            separateSellerProductList: 'OrionAlliance_NewModule/js/product/separate-seller-product-list',
            formButtonAction: 'OrionAlliance_NewModule/js/form-button-action',
            "OwlCarousel": "OrionAlliance_NewModule/js/sellerlideshow/owl.carousel.min",
            "WkSellerSlideShow": 'OrionAlliance_NewModule/js/sellerlideshow/WkSellerSlideShow',
            'Magento_Ui/js/form/element/date':  'OrionAlliance_NewModule/js/form/element/date',
            descriptionGallary: 'OrionAlliance_NewModule/js/description-gallery'
        }
    },
    paths: {
        "colorpicker": 'js/colorpicker'
    },
    "shim": {
        "colorpicker" : ["jquery"],
        "OwlCarousel" : ["jQuery"]
    }
};
