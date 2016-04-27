关于 dkm_dmp（DKM dispense manager platform）开发概要：
1、数据字段设计，非统计类，与平台相关的，加 dkm_ 前缀
2、关于状态字段，统一用 status， 0 表示正常， 1 为禁用， 2 待审核
4、关于返回的json格式，统一为 json_encode(array('state' => 3, 'data' => 'class file not exists!'))
    关于 state 说明
    1： 成功； 2：游戏ID或平台ID唯恐； 3： 处理的类文件不存在； 4：没有对应的平台配置参数
    5： 直接调用了父类里面需要重写的方法； 6： token失效； 7： 非法用户
5、关于登陆校验地址的，配置在 dkm_channel_game_key 表里面，因为有时候会是游戏测试状态的登陆校验地址，不能写在 dkm_channel 这张统一性的表里
6、登录认证地址：http://localhost:8080/user/getToken
请求方式：POST或者GET
参数：
    game_id：U8Server分配给当前游戏的appID
    channel_id：当前客户端的渠道ID
    session：当前渠道登录成功的参数(sid,token,sessionId等，一个或者多个)，这里格式各个渠道SDK可能不一样。
    sign：md5("appID="+appID+"channelID="+channelID+"extension="+extension+appKey);这里U8SDK抽象层按照格式，生成一个md5串，appKey是U8Server分配给游戏的AppKey

返回(JSON格式)：
    { 
      state: 1（登录认证成功）；其他失败
      data: 认证成功才有数据，否则为空
            {
                userID: U8Server生成的唯一用户ID
                sdkUserID: 渠道SDK那里用户的ID
                username: U8Server返回统一格式的用户名
                sdkUserName: 渠道SDK那里用户的用户名
                token: U8Server生成的token，用于游戏服务器登录认证使用
                extension: 扩展数据字段。有特殊需求的数据可以在这里添加。
            }
    }
