# ManiyaTech Homepage-Banner-Slider module for Magento 2

The Homepage Banner Slider module allows Magento 2 store owners to display a responsive and dynamic banner slider on the homepage. It supports multiple slides with separate desktop and mobile images, customizable titles, buttons, and linking options. Built using Owl Carousel, it includes configurable options such as autoplay, slide transitions, arrows, dots, and item counts per device type. The module is optimized for performance, accessibility, and Magento coding standards, supporting image lazy loading, preload, and SEO-friendly practices.

## How to install ManiyaTech_HomepageBannerSlider module

### Composer Installation

Run the following command in Magento 2 root directory to install ManiyaTech_HomepageBannerSlider module via composer.

#### Install

```
composer require maniyatech/magento2-homepage-banner-slider
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy -f
```

#### Update

```
composer update maniyatech/magento2-homepage-banner-slider
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy -f
```

Run below command if your store is in the production mode:

```
php bin/magento setup:di:compile
```

### Manual Installation

If you prefer to install this module manually, kindly follow the steps described below - 

- Download the latest version [here](https://github.com/maniyatech/magento2-homepage-banner-slider/archive/refs/heads/main.zip) 
- Create a folder path like this `app/code/ManiyaTech/HomepageBannerSlider` and extract the `main.zip` file into it.
- Navigate to Magento root directory and execute the below commands.

```
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy -f
```
