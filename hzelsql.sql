CREATE TABLE `arena_battle_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `attack_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '进攻用户ID',
  `defend_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '防守用户ID',
  `attack_user_rank` int(10) NOT NULL DEFAULT '0' COMMENT '进攻者对战前排名',
  `defend_user_rank` int(10) NOT NULL COMMENT '防守者对战前排名',
  `winner` int(11) NOT NULL DEFAULT '0' COMMENT '胜利者ID',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '胜利获得积分',
  `is_finish` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否挑战完成',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='对战记录表';

CREATE TABLE `arena_rank_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1:每日排名奖励  2: 赛季排名奖励',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '奖励等级',
  `min_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最低排名',
  `max_rank` int(10) NOT NULL DEFAULT '0' COMMENT '最高排名',
  `reward` varchar(255) NOT NULL DEFAULT '' COMMENT '奖励物品',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='排名奖励配置表';

CREATE TABLE `arena_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `is_npc` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否NPC',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COMMENT='竞技场用户表';

CREATE TABLE `common_buff` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '属性类型：',
  `alias` char(5) NOT NULL DEFAULT '0' COMMENT '属性别名4种类型：1.主动状态类 2.常驻类 3.被动状态类 4.收益类',
  `desc` varchar(64) NOT NULL DEFAULT '' COMMENT '属性说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COMMENT='buff配置表';

CREATE TABLE `common_card_pool` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `prop_id` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '道具ID',
  `quantity` varchar(64) NOT NULL DEFAULT '0' COMMENT '数量',
  `rate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '概率',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否参加抽奖',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='抽奖卡池';

CREATE TABLE `common_character_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `character_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '角色所属 1:斩魂；2影舞；3猎魔',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `con` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生命',
  `atk` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '攻击',
  `def` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '防御',
  `agi` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '攻速',
  `crit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '暴击',
  `resi` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '韧性',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1501 DEFAULT CHARSET=utf8mb4 COMMENT='角色等级属性表';

CREATE TABLE `common_character_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `alias` char(6) NOT NULL DEFAULT '' COMMENT '等级别名',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `exp` varchar(64) NOT NULL DEFAULT '0' COMMENT '达到该等级所需的经验值',
  `box` varchar(1024) NOT NULL DEFAULT '' COMMENT '偷宝箱配置：0宝箱获得金币数量，1宝箱获得魂石数量',
  `skill` varchar(16) NOT NULL DEFAULT '' COMMENT '技能配置：123技能最大等级',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=501 DEFAULT CHARSET=utf8mb4 COMMENT='人物等级';

CREATE TABLE `common_elve` (
  `elve_id` int(11) NOT NULL COMMENT '主键',
  `alias` char(6) NOT NULL DEFAULT '' COMMENT '精灵别名',
  `name` varchar(16) NOT NULL DEFAULT '0' COMMENT '精灵名称',
  `quality` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '品质',
  `init_level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '初始等级',
  `init_star` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '初始星级',
  `baptism_cost` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '洗练消耗费用',
  `increase` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下次升段时额外增加的费用数额',
  `max_level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大等级',
  `max_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大星级',
  `decompose_return` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分解返还碎片',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`elve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='精灵配置';

CREATE TABLE `common_elve_attr` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `elve_id` int(10) NOT NULL DEFAULT '0' COMMENT '精灵ID',
  `attr_id` varchar(8) NOT NULL DEFAULT '' COMMENT '属性KEY',
  `attr_value` int(10) NOT NULL DEFAULT '0' COMMENT '属性值',
  `min_rate` int(10) NOT NULL DEFAULT '0' COMMENT '洗练最小增幅',
  `max_rate` int(10) NOT NULL DEFAULT '0' COMMENT '洗练最大增幅',
  `growth` int(10) NOT NULL DEFAULT '0' COMMENT '成长性',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COMMENT='精灵属性表';

CREATE TABLE `common_elve_upgrade` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `alias` char(8) NOT NULL DEFAULT '' COMMENT '等级别名',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '等级',
  `quality_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ss升到该等级费用',
  `quality_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 's升到该等级费用',
  `quality_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'a升到该等级费用',
  `quality_4` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'b升到该等级费用',
  `quality_5` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'c升到该等级费用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='精灵升级配置表';

CREATE TABLE `common_food` (
  `id` int(11) NOT NULL COMMENT '主键',
  `alias` char(6) NOT NULL DEFAULT '' COMMENT '别名',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '食物名字',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '食物等级',
  `exp` varchar(64) NOT NULL DEFAULT '0' COMMENT '食用经验值',
  `cost` varchar(64) NOT NULL DEFAULT '0' COMMENT '食物购买价格(金币)',
  `increase` varchar(64) NOT NULL DEFAULT '0' COMMENT '递增数值',
  `condition` varchar(64) NOT NULL DEFAULT '' COMMENT '条件类型,相应数值;2种解锁条件类型1.角色等级，数值为角色等级。2.通关关卡，数值为关卡ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='食材表';

CREATE TABLE `common_level` (
  `level_id` int(10) NOT NULL COMMENT '关卡ID',
  `alias` varchar(16) NOT NULL DEFAULT '' COMMENT '关卡ID别名',
  `chapter` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '章节',
  `level_name` varchar(16) NOT NULL DEFAULT '' COMMENT '关卡名称',
  `offline_reward` varchar(64) NOT NULL DEFAULT '' COMMENT '离线奖励金币',
  `normal_kill_reward` varchar(64) NOT NULL DEFAULT '' COMMENT '击杀普通怪物奖励金币',
  `monster1` varchar(64) NOT NULL DEFAULT '' COMMENT '怪物配置1',
  `monster2` varchar(64) NOT NULL DEFAULT '' COMMENT '怪物配置2',
  `monster3` varchar(64) NOT NULL DEFAULT '' COMMENT '怪物配置3',
  `monster4` varchar(64) NOT NULL DEFAULT '' COMMENT '怪物配置4',
  `monster5` varchar(64) NOT NULL DEFAULT '' COMMENT '怪物配置5',
  `boss` varchar(64) NOT NULL DEFAULT '' COMMENT 'boss配置',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`level_id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='关卡配置';

CREATE TABLE `common_level_pass_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `level_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关卡ID',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '道具ID；common_prop表主键',
  `quantity` varchar(64) NOT NULL DEFAULT '0' COMMENT '奖励数量',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COMMENT='普通关卡通关奖励配置';

CREATE TABLE `common_prop` (
  `prop_id` int(11) NOT NULL COMMENT '主键',
  `alias` char(6) NOT NULL DEFAULT '' COMMENT '道具别名',
  `name` varchar(16) NOT NULL DEFAULT '0' COMMENT '道具名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '大类:1.核心货币;2.普通道具;3.特殊道具',
  `sub_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '小类：1金币;2魂石;3钻石;4洗练石;5技能点;6精灵结晶;7精灵碎片;8;精灵',
  `quality` tinyint(1) NOT NULL DEFAULT '0' COMMENT '精灵碎片品质：1=SS;2=S;3=A;4=B;5=C',
  `elve_id` tinyint(10) NOT NULL DEFAULT '0' COMMENT '精灵ID',
  `merge_need_quality` int(10) NOT NULL DEFAULT '0' COMMENT '合成精灵所需碎片数量',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`prop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='道具表';

CREATE TABLE `common_skill` (
  `skill_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `character_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '角色类型',
  `skill_num` varchar(20) NOT NULL COMMENT '技能标识（用来标志技能1,2,3）',
  `alias` char(6) NOT NULL DEFAULT '' COMMENT '技能ID',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '技能名称',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '技能等级',
  `upgrade` varchar(64) NOT NULL DEFAULT '' COMMENT '升级费用(技能点)',
  `condition` int(10) NOT NULL DEFAULT '0' COMMENT '解锁条件（角色等级）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COMMENT='技能配置表';

CREATE TABLE `common_sq` (
  `sq_id` int(11) NOT NULL COMMENT '主键',
  `alias` char(6) NOT NULL DEFAULT '' COMMENT '神器别名',
  `name` varchar(16) NOT NULL DEFAULT '0' COMMENT '神器名称',
  `init_level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '初始等级',
  `baptism_cost` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '洗练消耗费用',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '解锁条件',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`sq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='神器配置';

CREATE TABLE `common_sq_attr` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sq_id` int(10) NOT NULL DEFAULT '0' COMMENT '神器ID',
  `attr_id` varchar(8) NOT NULL DEFAULT '' COMMENT '属性KEY',
  `attr_value` int(10) NOT NULL DEFAULT '0' COMMENT '属性值',
  `min_rate` int(10) NOT NULL DEFAULT '0' COMMENT '洗练最小增幅',
  `max_rate` int(10) NOT NULL DEFAULT '0' COMMENT '洗练最大增幅',
  `growth` int(10) NOT NULL DEFAULT '0' COMMENT '成长性',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COMMENT='神器属性表';

CREATE TABLE `common_sq_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '等级',
  `cost` int(10) NOT NULL DEFAULT '0' COMMENT '升级费用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='神器等级配置表';

CREATE TABLE `common_wing` (
  `wing_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `alias` char(6) NOT NULL DEFAULT '' COMMENT '翅膀ID',
  `name` varchar(16) NOT NULL DEFAULT '' COMMENT '翅膀名称',
  `quality` int(10) NOT NULL COMMENT '品质',
  `cost_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '费用类型：1钻石；2观看广告',
  `cost` varchar(64) NOT NULL DEFAULT '0' COMMENT '费用',
  `condition_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '解锁条件类型:1.角色等级;2.通关关卡',
  `condition` int(10) NOT NULL DEFAULT '0' COMMENT '等级或者关卡值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`wing_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COMMENT='翅膀配置表';

CREATE TABLE `common_zb` (
  `zb_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `character_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '角色类型',
  `alias` char(6) NOT NULL DEFAULT '' COMMENT '道具别名',
  `name` varchar(16) NOT NULL DEFAULT '' COMMENT '道具名称',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '等级',
  `break` varchar(64) NOT NULL DEFAULT '0' COMMENT '升级费用',
  `increase` varchar(64) NOT NULL DEFAULT '0' COMMENT '升段费用增长',
  `condition` varchar(64) NOT NULL DEFAULT '0' COMMENT '解锁条件:角色等级',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`zb_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='装备配置表';

CREATE TABLE `eve_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `register_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总注册人数',
  `active_total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总活跃人数',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `date` (`date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='每日总数统计';

CREATE TABLE `friend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `friend_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '好友id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='好友关系表';

CREATE TABLE `friend_box_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `friend_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '好友id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `date_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '偷取宝箱日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='好友宝箱偷取记录';

CREATE TABLE `maintain_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '版本号',
  `title` varchar(20) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内容',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `online_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '上线状态',
  `ip_white_list` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP白名单',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='维护配置表';

CREATE TABLE `sys_admin` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常,',
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色ID',
  `create_time` int(11) unsigned NOT NULL COMMENT '注册时间',
  `last_login_time` int(11) unsigned DEFAULT NULL,
  `login_times` smallint(5) unsigned DEFAULT '0',
  `last_login_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='后台管理员';

CREATE TABLE `sys_menu_node` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '权限英文名称',
  `sort` smallint(6) unsigned DEFAULT '10000' COMMENT '排序',
  `level` tinyint(1) unsigned DEFAULT '0' COMMENT '层次',
  `pid` int(6) unsigned DEFAULT '0' COMMENT '父节点',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '0隐藏   1显示',
  `app` varchar(40) NOT NULL DEFAULT '' COMMENT '应用名',
  `controller` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名',
  `action` varchar(30) NOT NULL DEFAULT '' COMMENT '操作名称',
  `icon` varchar(20) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `param` varchar(50) NOT NULL DEFAULT '' COMMENT '额外参数',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `path` (`app`,`controller`,`action`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限节点表';

CREATE TABLE `sys_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态;0:禁用;1:正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='角色表';

CREATE TABLE `sys_role_node` (
  `role_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '角色 id',
  `node_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '节点id',
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `node_id` (`node_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色权限表';

CREATE TABLE `task_daily` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '名称(中文)',
  `title` varchar(10) NOT NULL COMMENT '标识',
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型，1-常驻任务，2-轮换任务',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='每日任务表';

CREATE TABLE `task_daily_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `task_title` varchar(10) NOT NULL COMMENT '每日任务的标识',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建(完成)时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='每日任务用户完成表';

CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `nickname` varchar(16) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `channel_id` varchar(32) NOT NULL DEFAULT '' COMMENT '来源渠道',
  `appid` varchar(64) NOT NULL DEFAULT '' COMMENT '来源appid',
  `openid` varchar(64) NOT NULL DEFAULT '' COMMENT '第三方用户id',
  `device_num` varchar(124) NOT NULL DEFAULT '' COMMENT '设备号',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `login_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_ws_login_time` int(11) NOT NULL DEFAULT '0' COMMENT 'websocket最后登录时间',
  `more` varchar(255) NOT NULL DEFAULT '' COMMENT '授权信息',
  `sex` tinyint(2) NOT NULL DEFAULT '0' COMMENT '性别;0:保密,1:男,2:女',
  `city` varchar(32) NOT NULL DEFAULT '' COMMENT '城市',
  `province` varchar(32) NOT NULL DEFAULT '' COMMENT '省份',
  `country` varchar(32) NOT NULL DEFAULT '' COMMENT '国家',
  `ip_region` varchar(32) NOT NULL DEFAULT '' COMMENT '地区',
  `is_robot` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是机器人',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `openid` (`openid`) USING BTREE,
  KEY `nickname` (`nickname`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1121 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

CREATE TABLE `user_certify` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '登录密码',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '邮箱',
  `realname` varchar(64) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `idcard_num` varchar(255) NOT NULL DEFAULT '' COMMENT '身份证号',
  `device_num` varchar(124) NOT NULL DEFAULT '' COMMENT '设备号',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

CREATE TABLE `user_character` (
  `character_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `character_type` int(11) NOT NULL DEFAULT '0' COMMENT '角色所属 1:斩魂；2影舞；3猎魔',
  `level` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当前等级',
  `exp` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `character_plot` varchar(1024) NOT NULL DEFAULT '' COMMENT '角色剧情',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `power` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色战力',
  PRIMARY KEY (`character_id`)
) ENGINE=InnoDB AUTO_INCREMENT=374 DEFAULT CHARSET=utf8mb4 COMMENT='角色表';



CREATE TABLE `user_character_change_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `old_character` varchar(64) NOT NULL DEFAULT '' COMMENT '原值',
  `new_character` int(11) NOT NULL DEFAULT '0' COMMENT '新值',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='转职日志';

CREATE TABLE `user_character_data` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `money` varchar(64) NOT NULL DEFAULT '0' COMMENT '金钱',
  `diamond` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '钻石',
  `crystal` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '精灵结晶',
  `soul` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '魂石',
  `forg` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '洗练石',
  `max_food_level` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '当前合成食物最大等级',
  `max_level` smallint(4) unsigned NOT NULL DEFAULT '1' COMMENT '当前最高等级',
  `max_pass_normal` smallint(4) unsigned NOT NULL DEFAULT '1' COMMENT '普通模式通关最高级别',
  `max_pass_hard` smallint(4) unsigned NOT NULL DEFAULT '1' COMMENT '深渊模式通关最高级别',
  `challenge_hard_count` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '剩余挑战深渊模式次数',
  `sweep_hard_count` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '剩余扫荡深渊模式次数',
  `reset_hard_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '深渊模式重置时间',
  `total_skill_point` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '技能点总数',
  `use_skill_point` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '已用技能点',
  `curr_character` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '出战角色  1:斩魂；2影舞；3猎魔',
  `open_story` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开场剧情：0:没走完开场剧情 1：已过',
  `character_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `offline_minute` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '离线时间：分钟',
  `power` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当前角色战力',
  `total_power` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总战力',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1125 DEFAULT CHARSET=utf8mb4 COMMENT='用户游戏数据';

CREATE TABLE `user_character_equip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `is_unlock` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否解锁',
  `character_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `slot` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '插槽 1001:武器',
  `foreign_key` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '外键：物品的主ID',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星级,段',
  `star` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星级,段',
  `extra` varchar(1024) NOT NULL DEFAULT '' COMMENT '额外属性',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=364 DEFAULT CHARSET=utf8mb4 COMMENT='创建人物身上的道具';

CREATE TABLE `user_character_equip_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `character_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1升星 2升级',
  `old_level` int(10) NOT NULL DEFAULT '0' COMMENT '原等级',
  `new_level` int(10) NOT NULL DEFAULT '0' COMMENT '新等级',
  `old_star` int(10) NOT NULL DEFAULT '0' COMMENT '原星级',
  `new_star` int(10) NOT NULL DEFAULT '0' COMMENT '新星级',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=373 DEFAULT CHARSET=utf8mb4 COMMENT='武器强化日志';

CREATE TABLE `user_character_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `slot` int(11) NOT NULL DEFAULT '0' COMMENT '插槽 1-15',
  `slot_value` int(11) NOT NULL DEFAULT '0' COMMENT '插槽值 目前是食物ID',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '类型 0：无；1：食物',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1906 DEFAULT CHARSET=utf8mb4 COMMENT='人物背包表';

CREATE TABLE `user_character_skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `character_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `skill_1` int(11) NOT NULL DEFAULT '0' COMMENT '技能1',
  `skill_2` int(11) NOT NULL DEFAULT '0' COMMENT '技能2',
  `skill_3` int(11) NOT NULL DEFAULT '0' COMMENT '技能3',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=364 DEFAULT CHARSET=utf8mb4 COMMENT='角色技能表';

CREATE TABLE `user_crystal_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：-1消耗;1获得',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '值',
  `origin_value` varchar(64) NOT NULL DEFAULT '' COMMENT '原值',
  `after_value` varchar(64) NOT NULL DEFAULT '0' COMMENT '新值',
  `extra_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他表的主键',
  `desc` varchar(64) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='精灵结晶';

CREATE TABLE `user_diamond_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：-1消耗;1获得',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '值',
  `origin_value` varchar(64) NOT NULL DEFAULT '' COMMENT '原值',
  `after_value` varchar(64) NOT NULL DEFAULT '0' COMMENT '新值',
  `extra_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他表的主键',
  `desc` varchar(64) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='钻石';

CREATE TABLE `user_economy_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `economy_type` int(11) NOT NULL DEFAULT '0' COMMENT '1金钱;2钻石;3精灵结晶;4魂石;5洗练石',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型：1获得,0消费',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '值',
  `origin_value` varchar(64) NOT NULL DEFAULT '' COMMENT '原值',
  `after_value` varchar(64) NOT NULL DEFAULT '0' COMMENT '新值',
  `extra_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他表的主键',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='经济日志';

CREATE TABLE `user_elve` (
  `user_elve_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `elve_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '精灵ID',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `star` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星级',
  `is_destroy` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已经销毁：0否 1是',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否出战',
  `quality` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '品质',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `consume_soul` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消费魂石',
  `consume_forg` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消费洗练石',
  PRIMARY KEY (`user_elve_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8070 DEFAULT CHARSET=utf8mb4 COMMENT='用户精灵';

CREATE TABLE `user_elve_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_elve_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户精灵ID',
  `attr_id` varchar(8) NOT NULL DEFAULT '0' COMMENT '精灵属性ID',
  `attr_value` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '精灵属性值',
  `curr_attr_value` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当前阶段洗练属性值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16139 DEFAULT CHARSET=utf8mb4 COMMENT='用户精灵属性';

CREATE TABLE `user_elve_shard` (
  `shard_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `elve_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '精灵ID',
  `quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `quality` tinyint(1) NOT NULL DEFAULT '0' COMMENT '质量',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '道具ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`shard_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='用户精灵碎片';

CREATE TABLE `user_elve_shard_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：-1消耗;1获得',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '值',
  `origin_value` varchar(64) NOT NULL DEFAULT '' COMMENT '原值',
  `after_value` varchar(64) NOT NULL DEFAULT '0' COMMENT '新值',
  `extra_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他表的主键',
  `desc` varchar(64) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COMMENT='精灵碎片日志';

CREATE TABLE `user_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '类型 1单个玩家邮件 2全服玩家邮件',
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `call` varchar(50) NOT NULL COMMENT '称呼',
  `content` text NOT NULL COMMENT '内容',
  `sign` varchar(50) NOT NULL COMMENT '落款',
  `annex` varchar(255) NOT NULL COMMENT '附件内容(json数据)',
  `annex_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '附件状态 0未提取 1已提取',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '邮件状态 0未读 1已读',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='用户邮件表';

CREATE TABLE `user_food_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：1购买 2合成 3消耗',
  `food_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他表的主键',
  `desc` varchar(64) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='经济日志';

CREATE TABLE `user_forg_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：-1消耗;1获得',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '值',
  `origin_value` varchar(64) NOT NULL DEFAULT '' COMMENT '原值',
  `after_value` varchar(64) NOT NULL DEFAULT '0' COMMENT '新值',
  `extra_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他表的主键',
  `desc` varchar(64) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='洗练石';

CREATE TABLE `user_login_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为新用户  1是   0不是',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COMMENT='用户登录日志表';

CREATE TABLE `user_luckdraw_batch_log` (
  `batch_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖类型:1单次;10十连抽',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消耗钻石',
  `is_free` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否使用免费名额',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`batch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COMMENT='用户卡池抽奖记录';

CREATE TABLE `user_luckdraw_prop_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `batch_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'user_luckdraw_batch_log主键',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '道具ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户卡池抽奖具体道具表';

CREATE TABLE `user_money_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：-1购买食材 -2强化武器',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '值',
  `origin_value` varchar(64) NOT NULL DEFAULT '' COMMENT '原值',
  `extra_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他表的主键',
  `desc` varchar(64) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COMMENT='经济日志';

CREATE TABLE `user_online_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '上线时间',
  `logout_time` int(11) NOT NULL DEFAULT '0' COMMENT '下线时间',
  `online_time` int(11) NOT NULL DEFAULT '0' COMMENT '在线时长',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `online_time` (`online_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=331 DEFAULT CHARSET=utf8mb4 COMMENT='用户在线时长记录表';

CREATE TABLE `user_pass_hard` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '通关关卡 记录最高',
  `team_user_id_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户深渊模式通关表';

CREATE TABLE `user_pass_normal` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `level` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '通关关卡 记录最高',
  `team_user_id_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户普通模式通关表';

CREATE TABLE `user_pass_normal_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `level_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关卡ID',
  `team_user_id_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='通关日志';

CREATE TABLE `user_recharge_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `money` int(10) NOT NULL DEFAULT '0' COMMENT '充值金额',
  `create_month` varchar(64) NOT NULL DEFAULT '' COMMENT '充值月份',
  `create_day` varchar(64) NOT NULL DEFAULT '' COMMENT '充值日期',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '充值时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COMMENT='经济日志';

CREATE TABLE `user_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `sign_time` int(10) unsigned NOT NULL COMMENT '签到时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户签到表';

CREATE TABLE `user_skill_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：-1消耗;1获得',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '值',
  `origin_value` varchar(64) NOT NULL DEFAULT '' COMMENT '原值',
  `after_value` varchar(64) NOT NULL DEFAULT '0' COMMENT '新值',
  `extra_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他表的主键',
  `desc` varchar(64) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COMMENT='技能点';

CREATE TABLE `user_soul_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：-1消耗;1获得',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '值',
  `origin_value` varchar(64) NOT NULL DEFAULT '' COMMENT '原值',
  `after_value` varchar(64) NOT NULL DEFAULT '0' COMMENT '新值',
  `extra_id` int(11) NOT NULL DEFAULT '0' COMMENT '其他表的主键',
  `desc` varchar(64) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COMMENT='魂石';

CREATE TABLE `user_sq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `sq_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '神器ID',
  `level` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `is_unlock` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否解锁',
  `is_enable` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否装备',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id,sq_id` (`user_id`,`sq_id`) USING BTREE,
  KEY `user_id` (`user_id`),
  KEY `sq_id` (`sq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='创建人物身上的神器';

CREATE TABLE `user_sq_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `sq_id` int(10) NOT NULL DEFAULT '0' COMMENT '神器ID',
  `attr_id` varchar(8) NOT NULL DEFAULT '0' COMMENT '属性ID',
  `attr_value` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '属性值',
  `curr_attr_value` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '当前阶段洗练属性值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `sq_id` (`sq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='用户神器属性';

CREATE TABLE `user_wing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `foreign_key` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '外键：物品的主ID',
  `is_unlock` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否解锁',
  `is_enable` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='创建人物身上的翅膀';

CREATE TABLE `worker_client_id` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `client_id` varchar(64) NOT NULL DEFAULT '' COMMENT 'client_id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=utf8mb4 COMMENT='client_id对应user_id';

CREATE TABLE `user_recharge_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `money` int(10) NOT NULL DEFAULT '0' COMMENT '充值金额',
  `create_month` varchar(64) NOT NULL DEFAULT '' COMMENT '充值月份',
  `create_day` varchar(64) NOT NULL DEFAULT '' COMMENT '充值日期',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '充值时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COMMENT='经济日志';

CREATE TABLE `common_monster` (
  `monster_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `alias` char(6) NOT NULL DEFAULT '' COMMENT '怪物别名',
  `name` varchar(16) NOT NULL DEFAULT '' COMMENT '怪物名称',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '怪物类型:1=小怪;2=BOSS;3=特殊怪',
  `grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '怪物等级',
  `con` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生命',
  `def` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '攻击',
  `atk` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '防御',
  `agi` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '攻速',
  `crit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '暴击',
  `resi` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '韧性',
  `skill` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '技能',
  `launch` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '技能发动概率',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`monster_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COMMENT='怪物配置';

CREATE TABLE `common_monster_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `alias` varchar(16) NOT NULL DEFAULT '' COMMENT '组合别名',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COMMENT='怪物组合';

CREATE TABLE `common_monster_group_sub` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `monster_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'common_monster表主键',
  `position` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '位置：0,1,2,3,4,5,6',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'common_monster_group主键',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COMMENT='怪物组合配置';

CREATE TABLE `quest_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `map_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '坐标地址',
  `module` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '地图块：1,2,3,4,5,6,7,8',
  `event` tinyint(1) NOT NULL DEFAULT '0' COMMENT '事件ID：0无事件;-1出生点;1城镇;2恶灵;3宝箱;4宝箱怪;5boss',
  PRIMARY KEY (`id`),
  UNIQUE KEY `coord` (`map_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COMMENT='冒险模式坐标地图';

CREATE TABLE `quest_map_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `quest_id` varchar(20) NOT NULL,
  `quest_grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '世界等级',
  `energy_limit` int(10) NOT NULL DEFAULT '0' COMMENT '体力上限',
  `events_limit` int(10) NOT NULL DEFAULT '0' COMMENT '事件上限',
  `events_range` varchar(100) NOT NULL COMMENT '事件数量区间',
  `area_1_monster` varchar(120) NOT NULL DEFAULT '' COMMENT '区域1怪物配置',
  `area_2_monster` varchar(120) NOT NULL DEFAULT '' COMMENT '区域2怪物配置',
  `area_3_monster` varchar(120) NOT NULL DEFAULT '' COMMENT '区域3怪物配置',
  `area_4_monster` varchar(120) NOT NULL DEFAULT '' COMMENT '区域4怪物配置',
  `boss_point_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Boss位置',
  `boss_monster` varchar(120) NOT NULL DEFAULT '' COMMENT 'Boss怪物配置',
  `boss_monster_lv` int(10) NOT NULL DEFAULT '0' COMMENT 'boss怪物等级',
  `box_monster` varchar(120) NOT NULL DEFAULT '' COMMENT '宝箱怪物配置',
  `monster_reward` varchar(120) DEFAULT NULL COMMENT '恶灵击杀奖励',
  `boss_reward` varchar(120) DEFAULT NULL COMMENT 'BOSS击杀奖励',
  `box_monster_reward` varchar(120) DEFAULT NULL COMMENT '宝箱怪奖励',
  `box_h_reward` varchar(120) DEFAULT NULL COMMENT '高级宝箱奖励',
  `box_m_reward` varchar(120) DEFAULT NULL COMMENT '中级宝箱奖励',
  `box_l_reward` varchar(120) DEFAULT NULL COMMENT '低级宝箱奖励',
  `luck_event_reward` varchar(120) DEFAULT NULL COMMENT '幸运事件奖励',
  `treasure_event_reward` varchar(120) DEFAULT NULL COMMENT '宝藏事件奖励',
  `luck_event_reward_addition` int(10) NOT NULL DEFAULT '0' COMMENT '幸运事件奖励加成',
  PRIMARY KEY (`id`),
  UNIQUE KEY `quest_grade` (`quest_grade`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COMMENT='冒险模式配置';

CREATE TABLE `quest_map_plots` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `alias` varchar(20) NOT NULL DEFAULT '' COMMENT '特殊事件ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '事件名称',
  `grade` int(10) NOT NULL COMMENT '触发世界等级',
  `character_type` tinyint(2) NOT NULL COMMENT '触发角色限制',
  `character_level` int(10) NOT NULL COMMENT '触发等级限制',
  `point_id` int(11) NOT NULL COMMENT '触发点位',
  `target_id` int(11) NOT NULL COMMENT '目标点位',
  `content` text NOT NULL COMMENT '文字内容',
  `monster` varchar(255) NOT NULL COMMENT '怪物配置',
  `reward` varchar(255) NOT NULL COMMENT '奖励配置',
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '父级事件ID(自增ID)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='冒险委托剧情配置';

CREATE TABLE `quest_map_plots_log` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
   `plot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '剧情id',
   `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
   `finish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '完成时间',
   PRIMARY KEY (`id`),
   UNIQUE KEY `uni` (`user_id`,`plot_id`) USING BTREE COMMENT '唯一索引'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='冒险剧情记录';

CREATE TABLE `quest_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `point_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当前所处点位',
  `quest_grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '世界等级',
  `energy_value` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '体力值',
  `energy_renew_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次体力恢复时间戳',
  `health_value` float(10,2) NOT NULL DEFAULT '1.00' COMMENT '生命值百分比',
  `unlock_area` varchar(20) NOT NULL DEFAULT '' COMMENT '已解锁区域(逗号分隔)',
  `death_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户死亡次数',
  `random_event_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '触发随机事件次数',
  `kill_monster_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '击杀恶灵次数',
  `kill_reward_monster_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '击杀悬赏恶灵次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='用户冒险模式数据';

CREATE TABLE `hard_user_pass` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '通关关卡 记录最高',
  `team_user_id_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='用户深渊模式通关表';

CREATE TABLE `hard_user_pass_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `level_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关卡ID',
  `team_user_id_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `team_user_id_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '队员',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='困难模式通关日志';

CREATE TABLE `hard_user_sweep_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `level_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关卡ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COMMENT='困难模式扫荡日志';

CREATE TABLE `friend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id,邀请链接上的用户id',
  `friend_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '好友id,接受邀请人id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='好友关系表';

CREATE TABLE `friend_box_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reward_total` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '宝箱物品数目',
  `monster_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '宝箱怪物等级配置，此值加角色等级为宝箱怪等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='好友宝箱配置';

CREATE TABLE `friend_box_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `friend_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '好友id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `date_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '偷取宝箱日期',
  `is_skip` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否跳过',
  `is_got` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已领取 0:否,1:是',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni` (`user_id`,`friend_id`,`date_time`) USING BTREE COMMENT '用户id,好友id,日期 唯一'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='好友宝箱偷取记录';

CREATE TABLE `friend_box_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `box_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝箱id,friend_box_log表id',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '物品id common_prop表prop_id',
  `quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖励的数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='好友系统物品奖励情况表';

CREATE TABLE `friend_box_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prop_id` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '物品id common_prop表prop_id',
  `rate` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '概率,此列相加总数为10',
  `quantity` float unsigned NOT NULL DEFAULT '1' COMMENT '奖励数量，角色等级的x倍',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni` (`prop_id`) USING BTREE COMMENT '物品唯一索引'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='好友宝箱奖励配置';

CREATE TABLE `friend_invite_reward` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '物品',
  `quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='成功邀请好友奖励表';

CREATE TABLE `task_daily` (
  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务ID',
  `config_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日任务配置ID',
  `date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '日期:20210521',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='每日任务';

CREATE TABLE `task_daily_complete_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `task_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='每日任务用户完成表';

CREATE TABLE `task_daily_config` (
  `config_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型，1-常驻任务;2-轮换任务',
  `sub_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '小类:1观看广告;2在线时长(分);3加速收益;4抽卡次数;5分享次数;6登陆;7食用食物;8合成食物;9杀怪数量;10好友宝箱;11升级武器;12升级精灵;13深渊扫荡;14竞技场挑战;15冒险击杀恶灵',
  `task_quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务目标数量',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='每日任务配置';

CREATE TABLE `task_daily_config_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `config_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日任务配置ID',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖励道具ID',
  `quantity` varchar(64) NOT NULL DEFAULT '0' COMMENT '奖励道具数量',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='任务配置奖励';

CREATE TABLE `activity` (
  `activity_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `activity_name` varchar(32) NOT NULL DEFAULT '' COMMENT '活动名称',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0关闭；1启用',
  `activity_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '活动类型：1=7天签到;2定时领钻石',
  `banner` varchar(255) NOT NULL DEFAULT '' COMMENT 'banner地址',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序：数字越小，越靠前',
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='活动';

CREATE TABLE `activity_signin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `activity_id` int(10) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `name` varchar(8) NOT NULL DEFAULT '' COMMENT '天',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序：数字越小，越靠前',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='签到活动配置';

CREATE TABLE `activity_signin_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `signin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '签到id',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖励道具ID',
  `quantity` varchar(64) NOT NULL DEFAULT '0' COMMENT '奖励道具数量',
  `is_double` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许双倍',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='签到活动奖励配置';

CREATE TABLE `activity_timer` (
  `timer_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `activity_id` int(10) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `time` char(8) NOT NULL DEFAULT '' COMMENT '时:分:秒',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`timer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='定时活动配置';

CREATE TABLE `activity_timer_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `activity_id` int(10) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `timer_id` int(10) NOT NULL DEFAULT '0' COMMENT '定时配置ID',
  `prop_id` int(10) NOT NULL DEFAULT '0' COMMENT '道具ID',
  `quantity` varchar(64) NOT NULL DEFAULT '0' COMMENT '道具数量',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='定时活动奖励配置';

CREATE TABLE `activity_user_reward_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `activity_id` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '活动ID',
  `config_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '配置ID:签到就是签到表配置ID',
  `reward` varchar(1024) NOT NULL DEFAULT '' COMMENT '领取的奖励',
  `is_double` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否双倍领取：0否；1是',
  `date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '日期',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='活动领取日志';

CREATE TABLE `collect_reward` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `prop_id` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '奖励道具ID',
  `quantity` varchar(64) NOT NULL DEFAULT '' COMMENT '奖励数量',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='收藏奖励配置';

CREATE TABLE `collect_reward_log` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取奖励用户ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='收藏奖励领取记录';

CREATE TABLE `exchange_code` (
  `code_id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '兑换码',
  `total_quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总可用次数',
  `use_quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已用次数',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`code_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='兑换码';

CREATE TABLE `exchange_code_log` (
  `log_id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'exchange_code表主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='兑换码使用记录';

CREATE TABLE `exchange_code_reward` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'exchange_code表主键',
  `prop_id` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '奖励道具ID',
  `quantity` varchar(64) NOT NULL DEFAULT '' COMMENT '奖励数量',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='兑换码奖励';

CREATE TABLE `quicken_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `money` varchar(64) NOT NULL DEFAULT '0' COMMENT '金币',
  `date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '日期',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='加速获取金币记录';


CREATE TABLE `quest_luck_event` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(8) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '别名',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '事件类型 1-幸运事件 2-厄运事件',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '事件内容',
  `quantity` tinyint(1) unsigned NOT NULL DEFAULT '10' COMMENT '概率',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COMMENT='冒险随机事件';

CREATE TABLE `quest_user_luck` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
 `random_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'quest_random_event表id',
 `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0-未使用，1-已使用',
 `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='冒险用户随机事件奖励';

CREATE TABLE `quest_user_event_log` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型',
    `type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '类型 1-打败boss,2-打败悬赏恶灵,3-打败普通恶灵,4-触发随机事件,5-用户死亡',
    `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='冒险用户事件记录';


