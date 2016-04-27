1、Admin.php 是统一对外曝光的类，具体 Channel 目录下的才是对应的渠道登陆验证逻辑；
2、Channel 目录下的文件命名统一为 'C + 渠道ID' ，类名命名为 'Auth_Channel_C+渠道ID'；
3、Channel 目录下的类，统一实现统一结构的构造方法： __construct($game_id, $channel_id)

