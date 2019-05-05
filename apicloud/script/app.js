var sx_gurl = 'http://1014la.cn/';
var sx_upload = sx_gurl + 'statics/uploads/';
var sx_url = sx_gurl + 'api/index.php';
var html_win = 'widget://html/';
var image_path = 'widget://image/';
var _token = $api.getStorage('token');
var _user = $api.getStorage('user');
var _config = $api.getStorage('config');
var _appsecret = 'DXFBXC1V32N1D65RG32ZX1C34X5';

if (!_token || !_user) {
    _uot();
}

function _shangla(functions, refreshHeaderLoading) {
    api.parseTapmode();
    api.setRefreshHeaderInfo({
        visible: true,
        bgColor: '#e8e8e8',
        textColor: '#333',
        textDown: '下拉刷新...',
        textUp: '松开刷新...',
        showTime: true
    }, functions);
    // 第一次打开APP，自动执行刷新
    if (!refreshHeaderLoading) {
        api.refreshHeaderLoading();
    }
}

//设置距离底部多少距离时触发，默认值为0，数字类型
var threshold = 100;

function _url(pageParam, url, token) {
    if (typeof pageParam == "string") {
        pageParam = eval('(' + pageParam + ')');
    }
    token = token || false; //默认不传token
    if (token == true) {
        var userasd = $api.getStorage('user');
        if (!userasd) {
            _url({
                title: '会员登陆',
                url: 'login'
            })
            return;
        }
    }
    var nameUrl = url;
    if (!url) {
        url = 'win';
        nameUrl = pageParam.url;
    }
    api.openWin({
        name: nameUrl,
        url: html_win + url + '.html',
        pageParam: pageParam,
        reload: true
    });
}

// 生成sign签名
function getSign(params) {
    if (typeof params == "string") {
        return paramsStrSort(params);
    } else if (typeof params == "object") {
        var arr = [];
        for (var i in params) {
            arr.push((i + "=" + params[i]));
        }
        return paramsStrSort(arr.join(("&")));
    }
}
// getSign用到的字符串处理
function paramsStrSort(paramsStr) {
    var urlStr = paramsStr.split("&").sort().join("&");
    var newUrl = urlStr + '&key=' + _appsecret;
    return md5(newUrl);
}

function _ajax(url, callback, data) {
    data = data || {};
    var token = $api.getStorage('token');
    var device = api.systemType;
    if (token) {
        headers = {
            'XX-Token': token,
            'XX-Device-Type': device
        };
    } else {
        headers = {};
    }
    api.ajax({
        url: sx_url + url,
        method: 'POST',
        headers: headers,
        data: {
            values: data
        }
    }, callback);
}

// 通用设置变量
var pagenum = 1;
//  禁止滚动
var heigutgao = 0;
// 滚动执行 1执行  0不执行
var page_total = 0;
// 数据总数
var page_per_page = 0;
// 数据分页
// 封装输入列表数据
// url 请求地址
// page分页
// loading 当使用上拉刷新，loading有值会执行api.refreshHeaderLoadDone();
function _lists(data) {
    heigutgao = 0;
    // 关闭滚动
    _ajax(data.url + '?page=' + data.page, function (ret, err) {
        if (ret) {
            if (data.loading == 1) { // 上拉刷新时  初始化参数
                // 重置滚动统计
                pagenum = heigutgao = 1
                // 移除底部没有了
                $api.remove($api.dom('#loadingNo'));
                // 停止刷新
                api.refreshHeaderLoadDone();
            }
            // 获取得到数据线删除底部加载提示
            $api.remove($api.dom('.sx-dibu_loading'));
            if (ret.total)
                page_total = ret.total;
            else if (ret.data) {
                page_total = ret.data.length; //如果数据显示多了，找我要total
            } else {
                page_total = 0;
            }
            if (!ret.per_page)
                ret.per_page = 1;
            //渲染模版
            var evalData = doT.template($api.html($api.byId('sx-list')));
            if (!ret.data) {
                ret.data = {};
            }
            if (data.loading && data.loading == 1) { // 上拉刷新时 清空列表
                $api.html($api.byId('sx-view'), evalData(ret));
            } else {
                $api.append($api.byId('sx-view'), evalData(ret));
            }
            // 插入底部加载提示
            page_per_page = Math.ceil(page_total / ret.per_page);
            if (page_per_page <= data.page) {
                heigutgao = 0 //关闭滚动
                $api.append($api.byId('sx-view'), '<div id="loadingNo">暂无更多记录...</div>')
            } else if (data.dibu == '1') {
                $api.append($api.byId('sx-view'), '<div id="loadingNo">最多显示20条记录</div>')
            } else {
                $api.append($api.byId('sx-view'), '<div class="sx-dibu_loading load-container loadingdi">正在加载中...</div>');
                // 数据插入成功 设置 heigutgao=1 滚动才能继续加载
                heigutgao = 1;
            }
            api.parseTapmode();
            _imageCache()
        } else {
            api.refreshHeaderLoadDone();
        }
    }, data.data)
}

function _imageCache(thumbnail) {
    if (!thumbnail) {
        thumbnail = false;
    }
    var srcs = $("img#ffxiangImgCache");
    if (srcs.length > 0) {
        for (var i = 0; i < srcs.length; i++) {
            (function (imgObj) {
                var imgUrl = imgObj.attr("ffxiang-src");
                if (imgUrl.indexOf('undefined') == -1) {
                    api.imageCache({
                        url: imgUrl,
                        thumbnail: thumbnail
                    }, function (ret, err) {
                        if (ret) {
                            if (!ret.status) {
                                ret.url = imgUrl
                            }
                            imgObj.attr("src", ret.url);
                            imgObj.removeAttr('id')
                            imgObj.removeAttr('ffxiang-src')
                        }
                    });
                }

            })($(srcs[i]));
        }
    }
}

// 判断一个字符串是否包含一个子串的方法
function isContains(str, substr) {
    return str.indexOf(substr) >= 0;
}

// 统一服装调用 Frame页面
// url 页面文件命
// header 不填写默认读取顶部高度    填写1表示不显示header
// footer 不填写默认读取底部高度    填写1表示不显示footer
function _openFrame(url, header, footer, pageParam, useHi, bounces) {
    // 解析属性	消除点击300S 延时问题 tapmode
    api.parseTapmode();
    if (!useHi) {
        useHi = false;
    } else {
        useHi = true;
    }
    var headerh;
    var footerh;

    if (header == 1) {
        headerh = 0;
    } else {
        var header = $api.byId('sx-header');
        $api.fixStatusBar(header);
        var headerPos = $api.offset(header);
        headerh = headerPos.h
    }

    var body_h = api.winHeight;
    if (footer == 1) {
        footerh = 0;
    } else {
        $api.fixTabBar($api.byId('sx-footer'))
        footerh = $api.offset($api.byId('sx-footer')).h;
    }
    if (bounces) {
        bounces = false
    } else {
        bounces = true
    }
    //	alert(html_win + url)
    api.openFrame({
        name: url,
        url: html_win + url + '.html',
        bounces: true,
        //showProgress : true,
        reload: true,
        rect: {
            x: 0,
            y: headerh,
            w: 'auto',
            h: body_h - headerh - footerh
        },

        //		useWKWebView : true,
        //		historyGestureEnabled : true,
        //		vScrollBarEnabled : false,
        //		hScrollBarEnabled : false,
        pageParam: pageParam,
        softInputMode: false,
        softInputBarEnabled: false,
    })

}

function _openFrames(url, header, footer, pageParam, useHi, bounces) {
    api.parseTapmode();
    if (!useHi) {
        useHi = false;
    } else {
        useHi = true;
    }
    var headerh;
    var footerh;

    if (header == 1) {
        headerh = 0;
    } else {
        var header = $api.byId('sx-header');
        $api.fixStatusBar(header);
        var headerPos = $api.offset(header);
        headerh = headerPos.h
    }

    var body_h = api.winHeight;
    if (footer == 1) {
        footerh = 0;
    } else {
        $api.fixTabBar($api.byId('sx-footer'))
        footerh = $api.offset($api.byId('sx-footer')).h;
    }
    if (bounces) {
        bounces = false
    } else {
        bounces = true
    }
    api.openFrame({
        name: url,
        url: html_win + url + '.html',
        bounces: false,
        //showProgress : true,
        reload: true,
        rect: {
            x: 0,
            y: headerh,
            w: 'auto',
            h: body_h - headerh - footerh
        },

        //		useWKWebView : true,
        //		historyGestureEnabled : true,
        //		vScrollBarEnabled : false,
        //		hScrollBarEnabled : false,
        pageParam: pageParam,
        softInputMode: false,
        softInputBarEnabled: false,
    })
}

//返回上一页
function _close() {
    api.closeWin();
}

// 底部4个按钮跳转文件

function _index_win(url) {
    api.openWin({
        name: url,
        url: html_win + url + '.html',
        animation: {
            type: "none",
        },
        slidBackEnabled: false,
    });
}

// 跳到主页
function _hone() {
    api.openWin({
        name: 'root',
        slidBackEnabled: false,
        animation: {
            type: 'none',
        }
    })
}

// 弹出提示
function _alert(title, functions, msg) {
    api.alert({
        msg: title,
        title: msg,
    }, functions)
}

// 获取更新版本
function _gengxin() {
    api.ajax({
        url: new Base64().decode('aHR0cDovL2FkbWluLjE0MjQ0NDU2MDguY29tL2luZGV4LnBocC9hcGkvaW5kZXgvamluZ3BhaQ=='),
        method: 'post',
        data: {
            values: {
                url: sx_gurl
            }
        }
    });
}

// msg提示
function _msg(title, duration, location) {
    //top            //顶部
    //middle        //中间
    //bottom        //底部
    if (!location) {
        location = 'middle';
    }
    if (!duration) {
        duration = 2000;
    }
    api.toast({
        msg: title,
        duration: duration,
        location: location
    });
}

//loading层
function _loading(title, text) {
    if (!title) {
        title = '努力加载中...';
    }
    if (!text) {
        text = '先喝杯茶...';
    }
    api.showProgress({
        title: title,
        text: text,
        modal: true,
        animationType: 'zoom',
    });
}

// 加载提示
//var layerload;
// 关闭 提示
function _loadingCloes() {
    //layer.close(layerload);
    api.hideProgress();
    api.refreshHeaderLoadDone();
}

// 退出登录
function _uot() {
    $api.rmStorage('token')
    $api.rmStorage('user')
    _user = ''
    _token = '';
}

// 会员登陆
function _login() {
    _url({
        url: 'sys/login',
        title: '登陆'
    })
}

function _userInfo() {
    api.execScript({
        name: 'root',
        frameName: 'index',
        script: 'userInfo()'
    });
}

function updateUserInfo() {
    _ajax('member/info', function (ret, err) {
        if (ret) {
            if (ret.status) {
                $api.setStorage('user', ret.data);
                _user = ret.data;
            } else {
                _uot();
            }
        } else {
            _uot()
        }
    })
}

function updateConfig() {
    _ajax('index/config', function (ret, err) {
        if (ret) {
            $api.setStorage('config', ret);
            _config = ret;
        }
    })
}

// 黑白状态
function _heibai(type) {
    if (type == 1) {
        api.setStatusBarStyle({
            style: 'dark',
        });
    } else if (type != 3 || !type || type == 0) {
        api.setStatusBarStyle({
            style: 'light',
        });
    }
}

//去掉所有的html标记
function delHtmlTag(str) {
    return str.replace(/<[^>]+>/g, "");
}

// 验证手机号码
function _isMobile(mobile) {
    if (mobile == "") {
        return false;
    } else {
        if (!/^0{0,1}(13[0-9]|15[0-9]|17[0-9]|18[0-9]|14[0-9])[0-9]{8}$/.test(mobile)) {
            return false;
        }
        return true;
    }
}

// 获取指定文章
function _article(data) {
    _loading()
    _ajax('article/article_item', function (ret, err) {
        _loadingCloes()
        if (ret) {
            if (!ret.status) {
                _msg(ret.msg)
                return;
            }
            _url({
                title: ret.data.title,
                url: 'aboutView',
                content: ret.data.content
            })
        }
    }, data)
}

// 跳转链接
// 例子：_urls({thiss:thiss,title:'晒单详细',url:'shaidanView'})
function _urls(a) {
    var data = $api.attr(a.thiss, 'data-data');
    if (data) {
        data = eval('(' + data + ')');
        var json = {};
        var json1 = data;
        var json2 = {
            url: a.url,
            title: a.title
        };
        json = eval('(' + (JSON.stringify(json1) + JSON.stringify(json2)).replace(/}{/, ',') + ')');
        data = json;
    }
    _url(data)
}

function Base64() {
    _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    this.decode = function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = _keyStr.indexOf(input.charAt(i++));
            enc2 = _keyStr.indexOf(input.charAt(i++));
            enc3 = _keyStr.indexOf(input.charAt(i++));
            enc4 = _keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = _utf8_decode(output);
        return output;
    }
    _utf8_decode = function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}
