angular.module('lltao.controllers', [])

/*
.controller('DashCtrl', function($scope) {
})
*/

.controller('AppsCtrl', function ($scope, $ionicPlatform, $ionicLoading, $interval, $timeout, $http, Apps) {
    $ionicLoading.show({
        content: '<img src="img/loader.gif">',
        showBackdrop: true,
        maxWidth: 100,
        showDelay: 0
    });
    $scope.empty = false;
    $timeout(function () {
        window.init.init(SERVER, device.imei);
        // 获取APP列表
        var promise = Apps.all();
        promise.then(function (data) {
            $scope.apps = data.apps;
            $ionicLoading.hide();
            if (data.length == 0) {
                $scope.empty = true;
            }
        }, function () {
            $scope.apps = [];
            $ionicLoading.hide();
            $scope.empty = true;
        });

        // 获取财富
        var url = SERVER + '/amount?imei=' + device.imei;
        var yuanbaoObj = document.getElementById("yuanbao");
        var yinbiObj = document.getElementById("yinbi");
        $interval(function () {
            $http({
                url: url,
                method: "GET",
                cache: false,
            }).success(function (data) {
                if (data.code == 0) {
                    var amount = data.data.amount;
                    var yuanbao = parseInt(data.data.amount / 100);
                    var yinbi = data.data.amount % 100;
                    //console.log(yuanbao);
                    yuanbaoObj = yuanbao + "元宝";
                    yinboObj = yinbi + "银币";
                }
            });
        }, 5000);

    }, 300);


    // hard back 按钮事件重新绑定
    $ionicPlatform.registerBackButtonAction(function () {
        navigator.app.exitApp();
    }, 100);
})

.controller('AppDetailCtrl', function ($scope, $stateParams, $ionicLoading, $interval, $timeout, $ionicPlatform, $ionicNavBarDelegate, $http, Apps) {
    $scope.app = Apps.get($stateParams.appId);
    $scope.down = true;
    $scope.downing = false;
    $scope.install = false;
    $scope.insting = false;
    $scope.exec = false;

    // 获取财富
    $interval(function () {
        var url = SERVER + '/amount?imei=' + device.imei;
        $http({
            url: url,
            method: "GET",
            cache: false,
        }).success(function (data) {
            if (data.code == 0) {
                var amount = data.data.amount;
                var yuanbao = parseInt(data.data.amount / 100);
                var yinbi = data.data.amount % 100;
                document.getElementById("yuanbao").textContent = yuanbao + "元宝";
                document.getElementById("yinbi").textContent = yinbi + "银币";
            }
        });
    }, 5000);

    // 展开
    $scope.op = function () {
        document.getElementById("summary").className = "dsummary";
        document.getElementById("sbtn").setAttribute('style', 'display:none');;
    };

    // 下载开始
    $scope.download = function () {
        $scope.loading = $ionicLoading.show({
            content: '下载中, 请稍后',
            showBackdrop: true,
            maxWidth: 300,
            showDelay: 200
        });

        // 提交开始事件
        var url = SERVER + '/operate';
        var postData = {
            imei: device.imei,
            package: $scope.app.package,
            action: 'download',
            token: ""
        };
        $http({
            url: url,
            method: "POST",
            cache: false,
            data: postData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
            }
        });

        // 循环读取进度
        var stop;
        $scope.fight = function () {
            if (angular.isDefined(stop)) return;

            stop = $interval(function () {
                var progress = 0.00;
                window.download.progress(function (pd) {
                    var progress = (pd * 100).toFixed(2);
                    $ionicLoading.show({
                        template: '下载中, 请稍后<br/>' + progress + '%'
                    });
                    if (progress == '100.00') {
                        $scope.stopFight();
                        $scope.down = false;
                        $scope.downing = false;
                        $scope.install = true;
                        $scope.insting = false;
                        $scope.exec = false;


                        $timeout(function () {
                            $scope.loading.hide();
                        }, 500)

                        // 提交下载完成事件
                        var url = SERVER + '/operate';
                        var postData = {
                            imei: device.imei,
                            package: $scope.app.package,
                            action: 'downloaded',
                            token: ""
                        };
                        $http({
                            url: url,
                            method: "POST",
                            cache: false,
                            data: postData,
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
                            }
                        });
                    }
                });

            }, 500);
        };

        // 更新进度取消
        $scope.stopFight = function () {
            if (angular.isDefined(stop)) {
                $interval.cancel(stop);
                stop = undefined;
            }
        };

        window.download.start($scope.app.link, function () {
            console.log('start');
            $scope.down = false;
            $scope.downing = true;
            $scope.install = false;
            $scope.insting = false;
            $scope.exec = false;
            $scope.fight();
        });
    };

    // 安装APP
    $scope.doInstall = function () {

        console.log('安装');

        $scope.down = false;
        $scope.downing = false;
        $scope.install = false;
        $scope.insting = true;
        $scope.exec = false;

        // 提交安装事件
        var url = SERVER + '/operate';
        var postData = {
            imei: device.imei,
            package: $scope.app.package,
            action: 'install',
            token: ""
        };
        $http({
            url: url,
            method: "POST",
            cache: false,
            data: postData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
            }
        });

        $timeout(function () {
            window.install.install($scope.app.link, $scope.app.package, $scope.app.award, function () {
                $scope.down = false;
                $scope.downing = false;
                $scope.install = false;
                $scope.insting = false;
                $scope.exec = true;
                console.log('安装完成');
            });
        }, 200);
    };

    // 运行APP
    $scope.execute = function () {
        // 提交安装事件
        var url = SERVER + '/operate';
        var postData = {
            imei: device.imei,
            package: $scope.app.package,
            action: 'execute',
            token: ""
        };
        $http({
            url: url,
            method: "POST",
            cache: false,
            data: postData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
            }
        });
        console.log('运行');
        window.install.execute($scope.app.package);
    };

    // hard back 按钮事件重新绑定
    $ionicPlatform.registerBackButtonAction(function () {
        if ($scope.downing) {
            window.download.stop();
            $scope.down = true;
            $scope.downing = false;
            $scope.install = false;
            $scope.insting = false;
            $scope.execute = false;
            $scope.stopFight();
            $scope.loading.hide();
            // navigator.app.exitApp();
        } else {
            $ionicNavBarDelegate.back();
        }
    }, 100);
})

.controller('AccountCtrl', function ($scope, $ionicLoading, $http, $timeout) {
    $scope.login = DEFAULT_LOGIN_STATE;

    // 拉取用户信息
    $scope.userinfo = function () {
        $ionicLoading.show({
            content: '<img src="img/loader.gif">',
            showBackdrop: true,
            maxWidth: 100,
            showDelay: 0
        });
        var url = SERVER + '/userinfo?imei=' + device.imei + '&token=' + $scope.token;
        console.log("拉取URL -------- " + url);
        $http({
            url: url,
            method: "GET",
            cache: false,
        }).success(function (data) {
            if (data.code == 0) {
                $scope.login = true;
                document.getElementById("avatar").src = data.data.avatar;
                document.getElementById("email").textContent = data.data.username;
                document.getElementById("nickname").textContent = "昵称：" + data.data.nickname;
                document.getElementById("points").textContent = "财富：" + parseInt(data.data.points / 100) + '元宝' + data.data.points % 100 + '银币';
                $timeout(function () {
                    $ionicLoading.hide();
                }, 300);
            } else {
                $ionicLoading.show({
                    content: "拉取失败，请重新登陆",
                    showBackdrop: true,
                    maxWidth: 200,
                    showDelay: 0
                });
                $timeout(function () {
                    $ionicLoading.hide();
                    $scope.logout();
                }, 800);
            }
        });
    };


    window.utils.getToken(function (token) {
        $scope.token = token;
        if ($scope.token == '') {
            $scope.login = false;
        } else {
            DEFAULT_LOGIN_STATE = true;
            $scope.userinfo();
        }

        console.log("读到TOKEN ------- " + token);
    });


    // 登陆
    $scope.doLogin = function () {
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        if (username.length == 0 || password.length == 0) {
            console.log("请输入账号和密码");
            $ionicLoading.show({
                content: '请输入账号和密码',
                showBackdrop: false,
                maxWidth: 200,
                showDelay: 0
            });
            $timeout(function () {
                $ionicLoading.hide();
            }, 800);

            return;
        }

        $ionicLoading.show({
            content: '登陆中',
            showBackdrop: true,
            maxWidth: 200,
            showDelay: 0
        });
        // 提交登陆
        var url = SERVER + '/signin';
        var postData = {
            imei: device.imei,
            username: username,
            password: password
        };
        console.log("账号：" + username + " ---- 密码：" + password);
        $http({
            url: url,
            method: "POST",
            cache: false,
            data: postData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
            }
        }).success(function (data) {
            if (data.code == 0) {
                $scope.token = data.data.token;
                $ionicLoading.hide();
                $scope.userinfo();
                window.utils.setToken(data.data.token);
                DEFAULT_LOGIN_STATE = true;
                console.log("储存token: ----- " + $scope.token);
            } else {
                console.log(data.msg);
                $ionicLoading.show({
                    content: data.msg,
                    showBackdrop: false,
                    maxWidth: 200,
                    showDelay: 0
                });
                $timeout(function () {
                    $ionicLoading.hide();
                }, 800);
            }
        });
    };

    // 登出
    $scope.logout = function () {
        console.log('登出');
        $scope.login = false;
        DEFAULT_LOGIN_STATE = false;
        window.utils.setToken("");
    };

})
    .controller('LogCtrl', function ($scope, $ionicLoading, $timeout, $http) {

        $ionicLoading.show({
            content: '<img src="img/loader.gif">',
            showBackdrop: false,
            maxWidth: 100,
            showDelay: 0
        });
        window.utils.getToken(function (token) {
            $scope.token = token;
            var url = SERVER + '/applog?imei=' + device.imei + '&token=' + $scope.token;
            console.log("拉取URL -------- " + url);
            $http({
                url: url,
                method: "GET",
                cache: false,
            }).success(function (data) {
                if (data.code == 0) {
                    $scope.logs = data.data.logs;

                    $ionicLoading.hide();
                } else {
                    $ionicLoading.show({
                        content: "拉取失败",
                        showBackdrop: false,
                        maxWidth: 200,
                        showDelay: 0
                    });
                    $timeout(function () {
                        $ionicLoading.hide();
                    }, 800);
                }
            });
        });
    });