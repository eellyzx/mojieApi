<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'ArenaDailySettle' => 'app\command\ArenaDailySettle', //竞技场每日排名结算
        'ArenaSeasonSettle' => 'app\command\ArenaSeasonSettle', //竞技场赛季排名结算
        'ArenaRecordDelHistory' => 'app\command\ArenaRecordDelHistory', //定期删除竞技场挑战记录
        'EmailDelHistory' => 'app\command\EmailDelHistory', //定期删除历史邮件
        'EmailDutyScan' => 'app\command\EmailDutyScan', //监测发送全服邮件
        'EmailDutyScanTime' => 'app\command\EmailDutyScanTime', //监测发送设置定时的全服邮件
        'combatPowerStatistics' => 'app\command\CombatPowerStatistics',//战力统计
        'genTaskDaily'             => 'app\command\GenTaskDaily',//重置每日任务
        'npcEvolution'          => 'app\command\NpcEvolution',//NPC成长
        'commonCommand'         => 'app\command\CommonCommand'
    ],
];
