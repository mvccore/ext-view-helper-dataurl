# MvcCore - Extension - View - Helper - Data Url

[![Latest Stable Version](https://img.shields.io/badge/Stable-v4.3.1-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-view-helper-dataurl/releases)
[![License](https://img.shields.io/badge/Licence-BSD-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.3-brightgreen.svg?style=plastic)

Get any file content by given relative or absolute path in data url format: `data:image/png;base64,iVBOR..`.

## Installation
```shell
composer require mvccore/ext-view-helper-dataurl
```

## Example
```php
<img src="<?php echo $this->DataUrl(__DIR__ . '/image.png'); ?>" />
```
```html
<img src="data:image/png;base64,iVBOR.." />
```