<h1 align="center">Translate</h1>

翻译的 SDK，目前支持「百度」「有道」翻译。其它翻译 API 欢迎大家添加。

## 安装
`composer require diankemao/translate`

## 对外接口
- trans(string $q, string $to, string $from = 'auto')  
作用：直接将带翻译字符串转换成目标语言  
返回值：string 目标语言字符串  
参数：  
$q: 待翻译的字符串  
$to: 目的语言    可选 - 默认 英文  
$from: 源语言   可选 - 默认 auto  

- link(string $q)  
作用：转换为 url 友好的链接格式  
返回值：string   
参数：  
$q: 带转换的中文字符串  

## 使用方法
```php
<?php

use Diankemao\Translate\Translate;
use Diankemao\Translate\Strategies\OrderStrategy;

$config = [
    // 可选
    'strategy' => OrderStrategy::class,
    'gateways' => [
        'baidu' => [
            'appid' => 'xxxx',
            'appsecret' => 'xxxx',
        ],
        'youdao' => [
            'appid' => 'xxxx',
            'appsecret' => 'xxxx',
        ],
    ],
    
];

$t = new Translate($config);

// 翻译
echo $t->trans("你好");
```

## 支持的翻译列表
- 百度
请传送至 [这里](http://api.fanyi.baidu.com/api/trans/product/apidoc)  

- 有道
请传送至 [这里](http://ai.youdao.com/docs/doc-trans-api.s#p05)

## License
MIT