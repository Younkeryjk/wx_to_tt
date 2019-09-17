# 将微信小程序转换为头条小程序

### 说明

***修改convert_wx_to_tt.php中的小程序所在目录，运行即可自动转换；***

***只修改了基本的不同部分，其他需要修改之处待完善。***

### 修改文件后缀名

- .wxml替换为.ttml
- .wxss替换为.ttss

### 修改文件内容

- .js文件：将wx.替换为tt.
- .ttml文件：
  直接替换wx:为tt:容易替换出错，故将标识字符串细化来进行替换
  1. .wxml替换为.ttml
  2. .wxss替换为.ttss
  3. wx:for替换为tt:for
  4. wx:key替换为tt:key
  5. wx:for-item替换为tt:for-item(wx:for已替换，可忽略)
  6. wx:for-index替换为tt:for-index(wx:for已替换，可忽略)
  7. wx:if替换为tt:if
  8. wx:elif替换为tt:elif
  9. wx:else替换为tt:else