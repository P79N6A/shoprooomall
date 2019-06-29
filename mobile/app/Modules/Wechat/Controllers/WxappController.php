<?php

namespace App\Modules\Wechat\Controllers;

use App\Modules\Base\Controllers\BackendController;

class WxappController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        L(require(MODULE_PATH . 'Language/' . C('shop.lang') . '/wechat.php'));
        $this->assign('lang', array_change_key_case(L()));
        // 初始化
        $this->init_params();
    }

    /**
     * 处理公共参数
     */
    private function init_params()
    {

    }

    /**
     * 小程序设置
     */
    public function actionIndex()
    {
        // 权限
        $this->admin_priv('wxapp_config');

        // 提交处理
        if (IS_POST) {
            $id = I('id', 0, 'intval');
            $data = I('post.data', '', 'trim');
            // 验证数据
            if (empty($data['wx_appid'])) {
                $this->message(L('must_appid'), null, 2);
            }
            if (empty($data['wx_appsecret'])) {
                $this->message(L('must_appsecret'), null, 2);
            }
            if (empty($data['wx_mch_id'])) {
                $this->message(L('must_mch_id'), null, 2);
            }
            if (empty($data['wx_mch_key'])) {
                $this->message(L('must_mch_key'), null, 2);
            }
            if (empty($data['token_secret'])) {
                $this->message(L('must_token_secret'), null, 2);
            }
            // 更新数据
            if (!empty($id)) {
                // 如果 wx_appsecret 包含 * 跳过不保存数据库
                if (strpos($data['wx_appsecret'], '*') == true) {
                    unset($data['wx_appsecret']);
                }
                dao('wxapp_config')->data($data)->where(array('id' => $id))->save();
            } else {
                $data['add_time'] = gmtime();
                dao('wxapp_config')->data($data)->add();
            }
            $this->message(L('wechat_editor') . L('success'), url('index'));
        }

        // 查询
        $info = dao('wxapp_config')->find();
        if (!empty($info)) {
            // 用*替换字符显示
            $info['wx_appsecret'] = string_to_star($info['wx_appsecret']);
        }

        $this->assign('data', $info);
        $this->display();
    }

    /**
     * 新增小程序
     */
    public function actionAppend()
    {

    }

    /**
     * 删除小程序
     */
    public function actionDelete()
    {
        $condition['id'] = input('id', 0, 'intval');
        dao('wxapp_config')->where($condition)->delete();
    }


}
