<?php
/**
 * 创建一个zip压缩文件
 * 打包内容为来自网络的文件
 *
 *
 */
class ZipHttpFile extends ZipArchive
{
  private static $switch = false;
  const BASE_PATH = "/tmp/";
  const TMP_FILE_PREFIX = "ZipHttpFile_";

  protected $fileds = [
  ];

  protected $httpFiles = [];

  protected $tmpFiles = [];

  public function __construct($name) {
    if(file_exists($name)) {
      echo "";
      return false;
    }
    if($this->open($name, ZipArchive::CREATE) !== true) {
      return false;
    }
    self::$switch = true;
  }

  public function __destruct() {
    $this->close();
    $this->_unlinkTmpFiles();
  }

  private function _unlinkTmpFiles() {
    foreach ($this->tmpFiles as $tmpFIle) {
      file_exists($tmpFIle) && unlink($tmpFIle);
    }
  }


  public function canUse() {
    return self::$switch;
  }

  public function execute() {
    foreach ($this->httpFiles as $zipName => $file) {
      $tmpFilePath = $this->_getTmpFilePath($zipName);
      if(!$this->_getHttpFile($file, $tmpFilePath)) {
        $this->fileds[] = [$zipName, $file];
      }
      $this->addFile($tmpFilePath, $zipName);
      $this->tmpFiles[] = $tmpFilePath;
    }
  }

  public function addHttpFiles(array $files) {
    if(!$this->canUse()) {
      return false;
    }
    foreach ($files as $name => $file) {
      $zipName = $this->_getNameFromUrl($name, $file);
      $this->httpFiles[$zipName] = $file;
    }
    return true;
  }

  public function getFieldFiles() {
    return $this->fileds;
  }

  private function _getTmpFilePath($name) {
    $path = self::BASE_PATH . self::TMP_FILE_PREFIX . time(true) . "_" . $name;
    return $path;
  }

  private function _getHttpFile($url, $path) {
    $fpTmp = fopen($path, "w");
    if(!$fpTmp) {
      return false;
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $fpTmp);
    $ret = curl_exec($ch);
    curl_close($ch);

    fclose($fpTmp);

    return $ret ? true : false;
  }

  /**
   *
   *  获取文件的打包名称，如果存在name且为字符串，直接使用，否则从http连接中获取文件名
   *
   */
  private function _getNameFromUrl($name, $url) {
    $_url = pathinfo($url, PATHINFO_BASENAME);
    return !is_numeric($name) ? $name : ($_url ? $_url : $name);
  }
}

$t = new ZipHttpFile("/tmp/yrt.test.zip");
$files = [
  'apk1.apk' => 'http://test-static.bj.bcebos.com/app/static/b27fa76d6cedb61b1a9206e14408d473.apk',
  'apk2.apk' => 'http://test-static.bj.bcebos.com/app/static/705ad81759bb2ef299cef2e2efb1c709.apk',
  'apk3.apk' => 'http://test-static.bj.bcebos.com/app/static/db0dd75506329dbbcabd1623dd3bfbd9.apk',
  'apk4.apk' => 'http://test-static.bj.bcebos.com/app/static/ee6da8f402285561762438f8e7cd11b9.apk',
  'apk5.apk' => 'http://test-static.bj.bcebos.com/app/static/5338ce40a00debd24a3cfafc34756644.apk',
];
$t->addHttpFiles($files);
$t->execute();
