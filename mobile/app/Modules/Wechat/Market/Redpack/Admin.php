<?php

namespace App\Modules\Wechat\Market\Redpack;

use App\Modules\Wechat\Controllers\PluginController;
use App\Extensions\QRcode;
use Think\Image;
use App\Extensions\Form;
use App\Extensions\Wechat;

/**
 * 现金红包后台模块
 * Class Admin
 * @package App\Modules\Wechat\Market\Redpack
 */
class Admin extends PluginController
{
    protected $marketing_type = ''; // 活动类型
    protected $wechat_id = 0; // 微信通ID
    protected $page_num = 10; // 分页数量

    // 配置
    protected $cfg = array();

    public function __construct($cfg = array())
    {
        parent::__construct();

        $file = array('payment');
        $this->load_helper($file);

        $this->cfg = $cfg;
        $this->cfg['plugin_path'] = 'Market';
        $this->plugin_name = $this->marketing_type = $cfg['keywords'];
        $this->wechat_id = $cfg['wechat_id'];
        $this->ru_id = isset($cfg['ru_id']) ? $cfg['ru_id'] : 0;
        $this->page_num = isset($cfg['page_num']) ? $cfg['page_num'] : 10;

        $this->assign('ru_id', $this->ru_id);
    }

    /**
     * 活动列表
     */
    public function marketList()
    {
        $filter['type'] = $this->marketing_type;
        $offset = $this->pageLimit(url('market_list', $filter), $this->page_num);

        $total = dao('wechat_marketing')->where(array('marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id))->count();

        $list = dao('wechat_marketing')->field('id, name, command, starttime, endtime, status')->where(array('marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id))->order('id DESC')->limit($offset)->select();
        if ($list[0]['id']) {
            foreach ($list as $k => $v) {
                $list[$k]['starttime'] = local_date('Y-m-d H:i', $v['starttime']);
                $list[$k]['endtime'] = local_date('Y-m-d H:i', $v['endtime']);
                $config = $this->get_market_config($v['id'], $v['marketing_type']);
                $list[$k]['hb_type'] = $config['hb_type'] == 1 ? L('group_redpack') : L('normal_redpack');
                $status = get_status($v['starttime'], $v['endtime']); // 活动状态 0未开始,1正在进行,2已结束
                if ($status == 0) {
                    $list[$k]['status'] = L('no_start');
                } elseif ($status == 1) {
                    $list[$k]['status'] = L('start');
                } elseif ($status == 2) {
                    $list[$k]['status'] = L('over');
                }
            }
        } else {
            $list = array();
        }

        $this->assign('page', $this->pageShow($total));
        $this->assign('list', $list);
        $this->plugin_display('market_list', $this->cfg);
    }

    /**
     * 活动添加与编辑
     * @return
     */
    public function marketEdit()
    {
        // 提交
        if (IS_POST) {
            $json_result = array('error' => 0, 'msg' => '', 'url' => ''); // 初始化通知信息

            $id = I('post.id', 0, 'intval');
            $data = I('post.data', '', 'trim');
            $config = I('post.config', '', 'trim');

            // 检查是否安装配置微信支付
            $payment = get_payment('wxpay');
            if (empty($payment)) {
                $json_result = array('error' => 1, 'msg' => '请先安装并配置微信支付');
                exit(json_encode($json_result));
            }
            // act_name 字段必填,并且少于32个字符
            if (empty($data['name']) || strlen($data['name']) >= 32) {
                $json_result = array('error' => 1, 'msg' => '活动名称必填，并且须少于32个字符');
                exit(json_encode($json_result));
            }
            // 红包金额必须在1元~200元之间
            if ($config['base_money'] < 1 || $config['base_money'] > 200) {
                $json_result = array('error' => 1, 'msg' => '红包金额必须在1元~200元之间，请重新填写');
                exit(json_encode($json_result));
            }
            // 红包发放总人数 普通红包固定为1，裂变红包至少为3
            if ($config['hb_type'] == 0 && $config['total_num'] != 1) {
                $json_result = array('error' => 1, 'msg' => '红包发放总人数 普通红包固定为1人, 请重新填写');
                exit(json_encode($json_result));
            }
            if ($config['hb_type'] == 1 && $config['total_num'] < 3) {
                $json_result = array('error' => 1, 'msg' => '红包发放总人数 裂变红包至少为3人, 请重新填写');
                exit(json_encode($json_result));
            }
            // nick_name 字段必填，并且少于16字符
            if (empty($config['nick_name']) || strlen($config['nick_name']) >= 16) {
                $json_result = array('error' => 1, 'msg' => '提供方名称必填，并且须少于16个字符');
                exit(json_encode($json_result));
            }
            // send_name 字段为必填，并且少于32字符
            if (empty($config['send_name']) || strlen($config['send_name']) >= 32) {
                $json_result = array('error' => 1, 'msg' => '红包发送方名称必填，并且须少于32个字符');
                exit(json_encode($json_result));
            }
            $data['wechat_id'] = $this->wechat_id;
            $data['marketing_type'] = I('post.marketing_type');
            $data['starttime'] = local_strtotime($data['starttime']);
            $data['endtime'] = local_strtotime($data['endtime']);

            $data['status'] = get_status($data['starttime'], $data['endtime']); // 活动状态 0未开始,1正在进行,2已结束

            $background_path = I('post.background_path', '', 'trim');
            // 编辑图片处理
            $background_path = edit_upload_image($background_path);

            // 上传背景图片
            if ($_FILES['background']['name']) {
                // 判断类型
                $type = array('image/jpeg', 'image/png');
                if ($_FILES['background']['type'] && !in_array($_FILES['background']['type'], $type)) {
                    // $this->message(L('not_file_type'), NULL, 2);
                    $json_result = array('error' => 1, 'msg' => L('not_file_type'));
                    exit(json_encode($json_result));
                }
                $result = $this->upload('data/attached/redpack', true);
                if ($result['error'] > 0) {
                    // $this->message($result['message'], NULL, 2);
                    $json_result = array('error' => 1, 'msg' => $result['message']);
                    exit(json_encode($json_result));
                }
            }
            //处理背景图片
            if ($_FILES['background']['name'] && $result['url']) {
                $data['background'] = $result['url'];
            } else {
                $data['background'] = $background_path;
            }

            // 验证
            $form = new Form();
            if (!$form->isEmpty($data['background'], 1)) {
                // $this->message(L('please_upload'), NULL, 2);
                $json_result = array('error' => 1, 'msg' => L('please_upload'));
                exit(json_encode($json_result));
            }

            // 生成证书
            if (!empty($payment)) {
                file_write("index.html", "");
                file_write("wxpay/index.html", "");
                file_write('wxpay/' . md5($payment['wxpay_appsecret']) . "_apiclient_cert.pem", $payment['sslcert']);
                file_write('wxpay/' . md5($payment['wxpay_appsecret']) . "_apiclient_key.pem", $payment['sslkey']);
            }
            //配置
            if ($config) {
                $data['config'] = serialize($config);
            }
            // 不保存默认空图片
            if (strpos($data['background'], 'no_image') !== false) {
                unset($data['background']);
            }
            //更新活动
            if ($id) {
                // 删除原背景图片
                if ($data['background'] && $background_path != $data['background']) {
                    $background_path = strpos($background_path, 'no_image') == false ? $background_path : '';  // 且不删除默认空图片
                    $this->remove($background_path);
                }
                $where = array(
                    'id' => $id,
                    'wechat_id' => $this->wechat_id,
                    'marketing_type' => $data['marketing_type']
                );
                dao('wechat_marketing')->data($data)->where($where)->save();
                // $this->message(L('market_edit') . L('success'), url('index'));
                $json_result = array('error' => 0, 'msg' => L('market_edit') . L('success'), 'url' => url('market_list', array('type' => $data['marketing_type'])));
                exit(json_encode($json_result));
            } else {
                //添加活动
                $data['addtime'] = gmtime();
                $id = dao('wechat_marketing')->data($data)->add();
                // $this->message(L('market_add') . L('success'), url('index'));
                $json_result = array('error' => 0, 'msg' => L('market_add') . L('success'), 'url' => url('market_list', array('type' => $data['marketing_type'])));
                exit(json_encode($json_result));
            }
        }

        // 显示
        $nowtime = gmtime();
        $info = array();
        $market_id = $this->cfg['market_id'];
        if (!empty($market_id)) {
            $info = dao('wechat_marketing')->field('id, name, command, logo, background, starttime, endtime, config, description, support')->where(array('id' => $market_id, 'marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id))->find();
            if ($info) {
                $info['starttime'] = isset($info['starttime']) ? local_date('Y-m-d H:i:s', $info['starttime']) : local_date('Y-m-d H:i:s', $nowtime);
                $info['endtime'] = isset($info['endtime']) ? local_date('Y-m-d H:i:s', $info['endtime']) : local_date('Y-m-d H:i:s', local_strtotime("+1 months", $nowtime));
                $info['config'] = unserialize($info['config']);
                $info['background'] = get_wechat_image_path($info['background']);
            } else {
                $this->message('数据不存在', url('market_list', array('type' => $this->marketing_type)), 2);
            }
        } else {
            // 默认开始与结束时间
            $info['starttime'] = local_date('Y-m-d H:i:s', $nowtime);
            $info['endtime'] = local_date('Y-m-d H:i:s', local_strtotime("+1 months", $nowtime));

            $info['config']['hb_type'] = 0;
            $info['config']['money_extra'] = 0;
            $info['config']['total_num'] = 1;

            // 取得最新ID
            $last_id = dao('wechat_marketing')->where(array('wechat_id' => $this->wechat_id))->order('id desc')->getField('id');
            $market_id = !empty($last_id) ? $last_id + 1 : 1;
        }

        // 微信素材所需活动链接
        $info['url'] = __HOST__ . url('wechat/index/market_show', array('type' => 'redpack', 'function' => 'activity', 'market_id' => $market_id, 'ru_id' => $this->ru_id));

        $this->assign('info', $info);
        $this->plugin_display('market_edit', $this->cfg);
    }

    /**
     * 摇一摇广告记录列表
     * @param market_id 活动ID
     * @param function 访问类型 如 shake
     * @param handler 操作类型 如 编辑
     * @return
     */
    public function marketShake()
    {
        $market_id = $this->cfg['market_id'];

        $function = I('get.function', '', 'trim');
        $handler = I('get.handler', '', 'trim');

        // 添加与编辑广告
        if ($handler && $handler == 'edit') {
            // 提交
            if (IS_POST) {
                $json_result = array('error' => 0, 'msg' => '', 'url' => ''); // 初始化通知信息

                $id = I('post.advertice_id', 0, 'intval');
                $data = I('post.advertice', '', 'trim');
                $icon_path = I('post.icon_path', '', 'trim');
                // 验证数据
                $form = new Form();
                if (!$form->isEmpty($data['content'], 1)) {
                    // $this->message(L('advertice_content') . L('empty'), NULL, 2);
                    $json_result = array('error' => 1, 'msg' => L('advertice_content'));
                    exit(json_encode($json_result));
                }
                // 验证url格式
                if (substr($data['url'], 0, 4) !== 'http') {
                    // $this->message(L('link_err'), NULL, 2);
                    $json_result = array('error' => 1, 'msg' => L('link_err'));
                    exit(json_encode($json_result));
                }

                $icon_path = edit_upload_image($icon_path);
                // 上传图片处理
                $file = $_FILES['icon'];
                if ($file['name']) {
                    $type = array('image/jpeg', 'image/png');
                    if (!in_array($file['type'], $type)) {
                        // $this->message(L('not_file_type'), NULL, 2);
                        $json_result = array('error' => 1, 'msg' => L('not_file_type'));
                        exit(json_encode($json_result));
                    }
                    $result = $this->upload('data/attached/redpack', true);
                    if ($result['error'] > 0) {
                        // $this->message($result['message'], NULL, 2);
                        $json_result = array('error' => 1, 'msg' => $result['message']);
                        exit(json_encode($json_result));
                    }
                    $data['icon'] = $result['url'];
                    $data['file_name'] = $file['name'];
                    $data['size'] = $file['size'];
                } else {
                    $data['icon'] = $icon_path;
                }

                if (!$form->isEmpty($data['icon'], 1)) {
                    // $this->message(L('please_upload'), NULL, 2);
                    $json_result = array('error' => 1, 'msg' => L('please_upload'));
                    exit(json_encode($json_result));
                }
                // 不保存默认空图片
                if (strpos($data['icon'], 'no_image') !== false) {
                    unset($data['icon']);
                }
                // 更新
                if ($id) {
                    // 删除原图片
                    if ($data['icon'] && $icon_path != $data['icon']) {
                        $icon_path = strpos($icon_path, 'no_image') == false ? $icon_path : '';  // 不删除默认空图片
                        $this->remove($icon_path);
                    }
                    $where = array('id' => $id, 'wechat_id' => $this->wechat_id);
                    dao('wechat_redpack_advertice')->data($data)->where($where)->save();
                    // $this->message(L('wechat_editor') . L('success'), url('shake', array('market_id' => $data['market_id'])));
                    $json_result = array('error' => 0, 'msg' => L('wechat_editor') . L('success'));
                    exit(json_encode($json_result));
                } else {
                    $data['wechat_id'] = $this->wechat_id;
                    dao('wechat_redpack_advertice')->data($data)->add();
                    // $this->message(L('add') . L('success'), url('shake', array('market_id' => $data['market_id'])));
                    $json_result = array('error' => 0, 'msg' => L('add') . L('success'));
                    exit(json_encode($json_result));
                }
            }
            // 显示单个广告信息
            $advertices_id = I('get.advertice_id', 0, 'intval');
            if ($advertices_id) {
                $condition = array(
                    'id' => $advertices_id,
                    'wechat_id' => $this->wechat_id
                );
                $info = dao('wechat_redpack_advertice')->where($condition)->find();
                if (empty($info)) {
                    $this->message('数据不存在', url('data_list', array('type' => $this->marketing_type, 'function' => $function, 'id' => $market_id)), 2);
                }
                $info['icon'] = get_wechat_image_path($info['icon']);
            }
            $where = array(
                'id' => $market_id,
                'wechat_id' => $this->wechat_id,
                'marketing_type' => $this->marketing_type,
            );
            $info['act_name'] = dao('wechat_marketing')->where($where)->getField('name');
            $this->assign('act_name', $info['act_name']);

            $this->assign('info', $info);
            $this->plugin_display('market_shake_edit', $this->cfg);
        } else {
            // 广告列表显示
            // 分页
            $filter['type'] = $this->marketing_type;
            $filter['function'] = $function;
            $filter['id'] = $market_id;
            $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);

            $condition = array(
                'market_id' => $market_id,
                'wechat_id' => $this->wechat_id
            );
            $total = dao('wechat_redpack_advertice')->where($condition)->count();
            // $page = $this->pageShow($total);
            $this->assign('page', $this->pageShow($total));

            $list = dao('wechat_redpack_advertice')->where($condition)->order('id desc')->limit($offset)->select();
            if ($list) {
                foreach ($list as $key => $value) {
                    $list[$key]['icon'] = get_wechat_image_path($value['icon']);
                }
            }

            // 当前活动名称
            $where = array(
                'id' => $market_id,
                'wechat_id' => $this->wechat_id,
                'marketing_type' => $this->marketing_type
            );
            $act_name = dao('wechat_marketing')->where($where)->getField('name');
            $this->assign('act_name', $act_name);

            $this->assign('list', $list);
            $this->plugin_display('market_shake', $this->cfg);
        }
    }

    /**
     * 活动记录
     * @return
     */
    public function marketLogList()
    {
        $market_id = $this->cfg['market_id'];

        $function = I('get.function', '', 'trim');
        $handler = I('get.handler', '', 'trim');

        if ($handler && $handler == 'info') {
            // 显示单条记录
            $log_id = I('get.log_id', 0, 'intval');
            if ($log_id) {
                $condition = array(
                    'id' => $log_id,
                    'wechat_id' => $this->wechat_id
                );
                $info = dao('wechat_redpack_log')->where($condition)->find();
                $info['nickname'] = dao('wechat_user')->where(array('wechat_id' => $this->wechat_id, 'openid' => $info['openid']))->getField('nickname');
                $info['time'] = !empty($info['time']) ? local_date('Y-m-d H:i:s', $info['time']) : '';

                // 接口查询更多详情
                if ($info['hassub'] == 1) {
                    $payment = get_payment('wxpay');
                    $options = array(
                         'appid' => $payment['wxpay_appid'], //填写高级调用功能的app id
                         'mch_id' => $payment['wxpay_mchid'], //微信支付商户号
                         'key' => $payment['wxpay_key'] //微信支付API密钥
                     );
                    $WxHongbao = new Wechat($options);
                    // 证书
                    $sslcert = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_cert.pem";
                    $sslkey = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_key.pem";

                    // 请求参数
                    $query_params = [
                        'mch_billno' => $info['mch_billno']
                    ];
                    $hb_type = $info['hb_type'] == 1 ? 'GROUP' : 'NORMAL';

                    $responseObj = $WxHongbao->QueryRedpack($query_params, $hb_type, $sslcert, $sslkey);
                    // logResult($responseObj);
                    $return_code = $responseObj->return_code;
                    $result_code = $responseObj->result_code;

                    if ($return_code == 'SUCCESS') {
                        if ($result_code == 'SUCCESS') {
                            // 显示返回的信息
                            $info['status'] = $responseObj->status; // 红包状态
                            $info['total_num'] = $responseObj->total_num; // 红包个数
                            $info['hb_type'] = $responseObj->hb_type; // 红包类型
                            $info['openid'] = $responseObj->openid; // 领取红包的Openid
                            $info['send_time'] = $responseObj->send_time; // 发送时间
                            $info['rcv_time'] = $responseObj->rcv_time;// 接收时间
                        } else {
                            // return $responseObj->return_msg;
                            // exit(json_encode(array('status' => 0, 'msg' => $responseObj->return_msg)));
                        }
                    } else {
                        // return $responseObj->return_msg;
                        // exit(json_encode(array('status' => 0, 'msg' => $responseObj->return_msg)));
                    }
                }

                $info['hb_type'] = $info['hb_type'] == 1 ? '裂变红包' : '普通红包';
                $info['hassub'] = $info['hassub'] == 1 ? '已领取' : '未领取';
            }

            $this->assign('info', $info);
            $this->plugin_display('market_log_info', $this->cfg);
        } else {
            // 记录列表
            // 分页
            $filter['type'] = $this->marketing_type;
            $filter['function'] = $function;
            $filter['id'] = $market_id;
            $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);
            $where = array(
                'wechat_id' => $this->wechat_id,
                'market_id' => $market_id
            );
            $total = dao('wechat_redpack_log')->where($where)->count();
            $list = dao('wechat_redpack_log')->where($where)->order('id desc')->limit($offset)->select();

            foreach ($list as $key => $value) {
                $list[$key]['nickname'] = dao('wechat_user')->where(array('wechat_id' => $this->wechat_id, 'openid' => $value['openid']))->getField('nickname');
                $list[$key]['time'] = !empty($value['time']) ? local_date('Y-m-d H:i:s', $value['time']) : '';
            }
            $this->assign('page', $this->pageShow($total));
            $this->assign('market_id', $market_id);
            $this->assign('redpacks', $list);

            $this->plugin_display('market_log_list', $this->cfg);
        }
    }

    /**
     * 设置分享 功能待定
     * @return
     */
    public function marketShare_setting()
    {
        $this->plugin_display('market_share_setting', $this->cfg);
    }


    /**
     * 活动二维码
     * @return
     */
    public function marketQrcode()
    {
        $market_id = I('get.id', 0, 'intval');

        if (!empty($market_id)) {
            $url = __HOST__ . url('wechat/index/market_show', array('type' => 'redpack', 'function' => 'activity', 'market_id' => $market_id, 'ru_id' => $this->ru_id));

            $info = dao('wechat_marketing')->field('qrcode')->where(array('id' => $market_id, 'marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id))->find();

            // 生成二维码
            // 纠错级别：L、M、Q、H
            $errorCorrectionLevel = 'M';
            // 点的大小：1到10
            $matrixPointSize = 7;
            // 生成的文件位置
            $path = dirname(ROOT_PATH) . '/data/attached/redpack/';
            // 水印logo
            $water_logo = ROOT_PATH . 'public/img/shop_app_icon.png';
            $water_logo_out = $path . 'water_logo' . $market_id . '.png';

            // 输出二维码路径
            $filename = $path . $errorCorrectionLevel . $matrixPointSize . $market_id . '.png';

            if (!is_dir($path)) {
                @mkdir($path);
            }
            QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

            // 添加水印
            $img = new Image();
            // 生成水印缩略图
            $img->open($water_logo)->thumb(80, 80)->save($water_logo_out);
            // 生成原图+水印
            $img->open($filename)->water($water_logo_out, 5, 100)->save($filename);

            $qrcode_url = __HOST__ . __STATIC__ . '/data/attached/redpack/' . basename($filename) . '?t=' . time();
            $this->cfg['qrcode_url'] = $qrcode_url;
        }

        $this->plugin_display('market_qrcode', $this->cfg);
    }

    /**
     * 将反序列化后的配置信息转换成数组格式
     * @param  [int] $id
     * @param  [string] $marketing_type
     * @return [array] array
     */
    public function get_market_config($id, $marketing_type)
    {
        $info = dao('wechat_marketing')->field('config')->where(array('id' => $id, 'marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id))->find();
        $result = unserialize($info['config']);
        return $result;
    }

    /**
     * 行为操作
     * @param handler 例如 删除
     */
    public function executeAction()
    {
        if (IS_AJAX) {
            $json_result = array('error' => 0, 'msg' => '', 'url' => '');

            $handler = I('get.handler', '', 'trim');
            $market_id = I('get.market_id', 0, 'intval');

            // 删除日志记录
            if ($handler && $handler == 'log_delete') {
                $log_id = I('get.log_id', 0, 'intval');
                if (!empty($log_id)) {
                    dao('wechat_redpack_log')->where(array('id' => $log_id, 'wechat_id' => $this->wechat_id, 'market_id' => $market_id))->delete();
                    $json_result['msg'] = '删除成功！';
                    exit(json_encode($json_result));
                } else {
                    $json_result['msg'] = '删除失败！';
                    exit(json_encode($json_result));
                }
            }

            // 搜索用户昵称
            if ($handler && $handler == 'search_nickname') {
                $keywords = I('nickname', '', 'trim');
                if (!empty($keywords)) {
                    $wechatUser = dao('wechat_user')
                        ->field('nickname, openid')
                        ->where('(nickname like "%' . $keywords . '%") and wechat_id = ' . $this->wechat_id)
                        ->order('uid DESC')
                        ->select();
                    if (!empty($wechatUser)) {
                        $json_result['status'] = 0;
                        $json_result['result'] = $wechatUser;
                        exit(json_encode($json_result));
                    } else {
                        $json_result['status'] = 1;
                        $json_result['msg'] = '未搜索到结果';
                        exit(json_encode($json_result));
                    }
                }
            }

            // 给指定微信用户（已关注）发送现金红包
            if ($handler && $handler == 'appoint_send_redpack') {
                $market_id = I('request.market_id', 0, 'intval');
                $re_openid = I('openid', '', 'trim');

                //活动配置
                $data = dao('wechat_marketing')->field('name, starttime, endtime, config')->where(array('id' => $market_id, 'marketing_type' => 'redpack', 'wechat_id' => $this->wechat_id))->find();

                $redpackinfo['config'] = unserialize($data['config']);

                $status = get_status($data['starttime'], $data['endtime']); // 活动状态 0未开始,1正在进行,2已结束
                if ($status == 0) {
                    $json_result = array(
                        'status' => 1,
                        'content' => '活动还没开始！',
                    );
                    exit(json_encode($json_result));
                } elseif ($status == 2) {
                    $json_result = array(
                        'status' => 1,
                        'content' => '活动已结束！',
                    );
                    exit(json_encode($json_result));
                } else {
                    $payment = get_payment('wxpay');
                    // 调用红包类
                    $options = [
                        'appid' => $payment['wxpay_appid'],
                        'mch_id' => $payment['wxpay_mchid'],
                        'key' => $payment['wxpay_key']
                    ];
                    $WxHongbao = new Wechat($options); //new WxHongbao($configure);

                    $sslcert = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_cert.pem";
                    $sslkey = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_key.pem";

                    // 设置参数
                    $mch_billno = $payment['wxpay_mchid'] . date('YmdHis') . rand(1000, 9999);
                    // 红包金额
                    $money = $redpackinfo['config']['base_money'] + rand(0, $redpackinfo['config']['money_extra']);
                    $money = $money * 100; // 转换为分
                    $hb_type = $redpackinfo['config']['hb_type'];
                    if ($hb_type == 0) {
                        $total_num = 1;
                    } else {
                        $total_num = $total_num > 3 ? $total_num : 3; // 裂变红包发放总人数，最小3人
                    }

                    $nick_name = $redpackinfo['config']['nick_name'];
                    $send_name = $redpackinfo['config']['send_name'];
                    $wishing = $redpackinfo['config']['wishing'];
                    $act_name = $redpackinfo['config']['act_name'];  //活动名称
                    $remark = $redpackinfo['config']['remark'];
                    // 场景ID
                    $scene_id = strtoupper($redpackinfo['config']['scene_id']);

                    if ($hb_type == 0) {
                        $parameters = [
                            'mch_billno' => $mch_billno,
                            'nick_name' => $nick_name,
                            'send_name' => $send_name,
                            're_openid' => $re_openid,
                            'total_amount' => $total_amount,
                            'min_value' => $min_value,
                            'max_value' => $max_value,
                            'total_num' => $total_num,
                            'wishing' => $wishing,
                            'client_ip' => $_SERVER['REMOTE_ADDR'],
                            'act_name' => $act_name,
                            'remark' => $remark,
                        ];
                    } elseif ($hb_type == 1) {
                        $parameters = [
                            'mch_billno' => $mch_billno,
                            'nick_name' => $nick_name,
                            'send_name' => $send_name,
                            're_openid' => $re_openid,
                            'total_amount' => $total_amount,
                            'total_num' => $total_num,
                            'amt_type' => "ALL_RAND",
                            'wishing' => $wishing,
                            'act_name' => $act_name,
                            'remark' => $remark,
                        ];
                    }
                    // 发放红包使用场景，红包金额大于200时必传
                    if ($scene_id && $scene_id > 0) {
                        $parameters["scene_id"] = $scene_id;
                    }
                    $responseObj = $WxHongbao->CreatSendRedpack($parameters, $hb_type, $sslcert, $sslkey);
                    if ($responseObj->return_code == 'SUCCESS') {
                        if ($responseObj->result_code == 'SUCCESS') {
                            $total_amount = $responseObj->total_amount * 1.0 / 100;
                            $re_openid = $responseObj->re_openid;
                            $mch_billno = $responseObj->mch_billno;
                            $mch_id = $responseObj->mch_id;
                            $wxappid = $responseObj->wxappid;

                            // 返回成功更新
                            $where = array(
                                'wechat_id' => $this->wechat_id,
                                'market_id' => $this->market_id,
                                'openid' => !empty($re_openid) ? $re_openid : $re_openid,
                            );
                            $data = array(
                                'hassub' => 1,
                                'money' => $total_amount,
                                'time' => gmtime(),
                                'mch_billno' => $mch_billno,
                                'mch_id' => $mch_id,
                                'wxappid' => $wxappid,
                                'bill_type' => 'MCHT',
                                'notify_data' => serialize($responseObj),
                            );
                            $result = dao('wechat_redpack_log')->data($data)->where($where)->save();
                            $json_result = array(
                                'status' => 0,
                                'content' => "红包发放成功！金额为：" . $total_amount . "元！",
                            );
                            exit(json_encode($json_result));
                            // return "红包发放成功！金额为：" . $total_amount . "元！";
                        }
                    } else {
                        if ($responseObj->err_code == 'NOTENOUGH') {
                            $json_result = array(
                                'status' => 1,
                                'content' => "红包已经发放完！！",
                            );
                            exit(json_encode($json_result));
                            // return "红包已经发放完！！!";
                        } elseif ($responseObj->err_code == 'TIME_LIMITED') {
                            $json_result = array(
                                'status' => 1,
                                'content' => "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取",
                            );
                            exit(json_encode($json_result));
                            // return "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
                        } elseif ($responseObj->err_code == 'SYSTEMERROR') {
                            $json_result = array(
                                'status' => 1,
                                'content' => "系统繁忙，请稍后再试！",
                            );
                            exit(json_encode($json_result));
                            // return "系统繁忙，请稍后再试！";
                        } elseif ($responseObj->err_code == 'DAY_OVER_LIMITED') {
                            $json_result = array(
                                'status' => 1,
                                'content' => "今日红包已达上限，请明日再试！",
                            );
                            exit(json_encode($json_result));
                            // return "今日红包已达上限，请明日再试！";
                        } elseif ($responseObj->err_code == 'SECOND_OVER_LIMITED') {
                            $json_result = array(
                                'status' => 1,
                                'content' => "每分钟红包已达上限，请稍后再试！",
                            );
                            exit(json_encode($json_result));
                            // return "每分钟红包已达上限，请稍后再试！";
                        }
                        $json_result = array(
                            'status' => 1,
                            'content' => "红包发放失败！" . $responseObj->return_msg . "！请稍后再试！",
                        );
                        exit(json_encode($json_result));
                        // return "红包发放失败！" . $responseObj->return_msg . "！请稍后再试！";
                    }
                }

            }

        }
    }

    /**
     * 获取数据
     */
    public function returnData($fromusername, $info)
    {
    }

    /**
     * 积分赠送
     *
     * @param unknown $fromusername
     * @param unknown $info
     */
    public function updatePoint($fromusername, $info)
    {
    }

    /**
     * 页面显示
     */
    public function html_show()
    {
    }
}
