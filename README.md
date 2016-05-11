# HTTP_ZIP
php打包http链接对应的文件列表

```php
$t = new ZipHttpFile("/tmp/yrt.test.zip");
var_dump($t->getErrMsg());
$files = [
  'apk1.apk' => 'http://test-static.bj.bcebos.com/app/static/b27fa76d6cedb61b1a9206e14408d473.apk',
  'apk2.apk' => 'http://test-static.bj.bcebos.com/app/static/705ad81759bb2ef299cef2e2efb1c709.apk',
  //'apk3.apk' => 'http://test-static.bj.bcebos.com/app/static/db0dd75506329dbbcabd1623dd3bfbd9.apk',
  //'apk4.apk' => 'http://test-static.bj.bcebos.com/app/static/ee6da8f402285561762438f8e7cd11b9.apk',
  'apk5.apk' => 'http://test-static.bj.bcebos.com/app/static/5338ce40a00debd24a3cfafc34756644.apk',
];
$t->addHttpFiles($files);
$t->addHttpFile('http://test-static.bj.bcebos.com/app/static/db0dd75506329dbbcabd1623dd3bfbd9.apk', "nihao.apk");
$t->addHttpFile('http://test-static.bj.bcebos.com/app/static/db0dd75506329dbbcabd1623dd3bfbd9.apk');
$t->handle();
```
