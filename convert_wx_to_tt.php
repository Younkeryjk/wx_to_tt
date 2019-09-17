<?php

class Convert_wx_to_tt
{
    private $project_dir;
    private $ext_rules;
    public function __construct($project_dir, $ext_rules = array('wxml' => 'ttml', 'wxss' => 'ttss'))
    {
        $this->project_dir = $project_dir;
        $this->ext_rules = $ext_rules;

        //修改根目录app.wxss后缀及app.js内容
        $this->convert_root();

        //批量修改文件后缀：.wxml转换为.ttml，.wxss转换为.ttss
        $this->convert_ext($this->project_dir.'/'.'pages', $this->ext_rules);

        //批量修改语法部分
        $this->convert_text($this->project_dir.'/'.'pages');
    }

    /**
     *
     */
    private function convert_root() {
        //修改根目录.wxss文件后缀
        rename($this->project_dir.'/'.'app.wxss', $this->project_dir.'/'.'app.ttss');

        //修改根目录app.js文件语法部分
        $app_js = $this->project_dir.'/'.'app.js';
        $text = file_get_contents($app_js);
        $text = str_replace('wx.', 'tt.', $text);
        file_put_contents($app_js, $text);
    }

    /**
     * 批量修改文件后缀名
     * @param $path 文件夹路径
     * @param $ext_rules 文件后缀替换规则
     * @return void
     */
    private function convert_ext($path, $ext_rules)
    {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        $this->convert_ext($path . '/' . $file, $ext_rules);
                    } else {
                        $path_info = pathinfo($file);
                        $ext = $path_info['extension'];
                        $all_exts = array_keys($ext_rules);
                        if (in_array($ext, $all_exts)) {
                            $src = $path . '/' . $file;
                            $dext = $ext_rules[$ext];
                            $fileName = $path_info['filename'];
                            $dest = $path . '/' . $fileName . '.' . $dext;
                            rename($src, $dest);
                        }
                    }
                }
            }
        }
    }


    /*
     * js文件：
     * 1、wx.替换为tt.
     * .ttml文件：
     * 直接替换wx:为tt:容易替换出错，故将标识字符串细化来进行替换
     * 1、.wxml替换为.ttml
     * 2、.wxss替换为.ttss
     *
     * 循环：
     * 3、wx:for替换为tt:for
     * 4、wx:key替换为tt:key
     * 5、wx:for-item替换为tt:for-item(wx:for已替换，可忽略)
     * 6、wx:for-index替换为tt:for-index(wx:for已替换，可忽略)
     * 条件：
     * 7、wx:if替换为tt:if
     * 8、wx:elif替换为tt:elif
     * 9、wx:else替换为tt:else
     */
    private function convert_text($path)
    {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        $this->convert_text($path . '/' . $file);
                    } else {
                        $path_info = pathinfo($file);
                        $ext = $path_info['extension'];
                        $src = $path . '/' . $file;
                        if ('js' == $ext) {
                            $text = file_get_contents($src);
                            $text = str_replace('wx.', 'tt.', $text);
                            file_put_contents($src, $text);
                        }
                        if ('ttml' == $ext) {
                            $text = file_get_contents($src);
                            $text = str_replace('.wxml', '.ttml', $text);
                            $text = str_replace('.wxss', '.ttss', $text);
                            $text = str_replace('wx:for', 'tt:for', $text);
                            $text = str_replace('wx:key', 'tt:key', $text);
                            $text = str_replace('wx:if', 'tt:if', $text);
                            $text = str_replace('wx:elif', 'tt:elif', $text);
                            $text = str_replace('wx:else', 'tt:else', $text);
                            file_put_contents($src, $text);
                        }
                    }
                }
            }
            return false;
        }
    }
}

$obj = new Convert_wx_to_tt('E:/ttxcx');
exit('success');