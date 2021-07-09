<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

// 好友
Route::group('friend', function () {

    // 设置助战好友
    Route::post('/set-help', 'game.Friend/setHelpFriend');

    // 获得助战好友信息
    Route::get('/get-info','game.friend/getHelpFriendInfo');
});

// 冒险
Route::group('quest', function () {

    // 设置中间事件
    Route::post('/set-middle', 'quest.Quest/setMiddleEvent');
});

//精灵
Route::group('elve', function (){
    Route::rule('/elve/getAllElve', 'elve.elve/getAllElve');
    Route::rule('/elve/refine', 'elve.elve/refine');
    Route::rule('/elve/confirmRefine', 'elve.elve/confirmRefine');
    Route::rule('/elve/upgrade', 'elve.elve/upgrade');
    Route::rule('/elve/getBattleElve', 'elve.elve/getBattleElve');
    Route::rule('/elve/upgradeStar', 'elve.elve/upgradeStar');
    Route::rule('/elve/decompose', 'elve.elve/decompose');
    Route::rule('/elve/getDecomposeResources', 'elve.elve/getDecomposeResources');
    Route::rule('/elve/reset', 'elve.elve/reset');
    Route::rule('/elve/getResetResources', 'elve.elve/getResetResources');
    Route::rule('/elve/getUserElveShard', 'elve.elve/getUserElveShard');
    Route::rule('/elve/merge', 'elve.elve/merge');
    Route::rule('/elve/chooseElve', 'elve.elve/chooseElve');
    Route::rule('/elve/unChooseElve', 'elve.elve/unChooseElve');
});
//活动
Route::group('activity', function (){
    Route::rule('/activity/getAllActivity', 'activity.activity/getAllActivity');
    Route::rule('/signin/getDayList', 'activity.signin/getDayList');
    Route::rule('/signin/receiveReward', 'activity.signin/receiveReward');
    Route::rule('/timer/getTimerActivity', 'activity.timer/getTimerActivity');
    Route::rule('/timer/receiveReward', 'activity.timer/receiveReward');
});
//广告
Route::group('ad', function (){
    Route::rule('/ad_log/reportAdLog', 'ad.adLog/reportAdLog');
    Route::rule('/adLog/reportAdLog', 'ad.adLog/reportAdLog');
});

//控制器添加路由,处理多文件的点号
Route::get(':module/:controller/:action',':module.:controller/:action')->allowCrossDomain();
Route::post(':module/:controller/:action',':module.:controller/:action')->allowCrossDomain();