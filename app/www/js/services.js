angular.module('lltao.services', [])

.factory('Apps', function($http, $q) {
  var apps = [];

  return {
    all: function() {
        var url = SERVER + '/apps';
        var install;
        window.utils.installed(function(list) {
           install = list;
        });
        
//         console.log(install);
        var postData = {imei: device.imei, apps: eval("("+install+")")};
        var deferred = $q.defer();
        $http({
            url:url, 
            method:"POST",
            cache:false,
            data: postData,
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'}
        }).success(function(data) {
            if(data.code == 0) {
                apps = data.data;
                deferred.resolve(apps);
            }
        }).error(function() {
            deferred.reject();
        });
        
        return deferred.promise;
    },
    get: function(appId) {
      return apps.apps[appId];
    }
  }
});
