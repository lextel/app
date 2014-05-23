package so.doo.app.plugins;

import org.apache.cordova.CallbackContext;
import org.apache.cordova.CordovaInterface;
import org.apache.cordova.CordovaPlugin;
import org.apache.cordova.CordovaWebView;
import org.json.JSONArray;
import org.json.JSONException;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.os.RemoteException;
import android.util.Log;

public class Install extends CordovaPlugin {
	
	private static Context ctx;
    private static final String TAG = "INTALL";

    public void initialize(CordovaInterface cordova, CordovaWebView webView) {
        super.initialize(cordova, webView);
        ctx = cordova.getActivity().getApplicationContext();
    }
    
    /**
     * Executes the request and returns PluginResult.
     *
     * @param action            The action to execute.
     * @param args              JSONArry of arguments for the plugin.
     * @param callbackContext   The callback id used when calling back into JavaScript.
     * @return                  True if the action was valid, false otherwise.
     * @throws JSONException 
     */
    public boolean execute(String action, JSONArray args, CallbackContext callbackContext) throws JSONException {

        // install
        if (action.equals("install")) {
        	
        	String url     = args.getString(0);
        	String appname = args.getString(1);
        	int    point   = args.getInt(2);
        	String path = Download.Utils.saveDir() +"/"+ Download.Utils.getFileName(url);
        	
        	Log.i(TAG, "安装软件包路径 ---- " + path);
        	
            int rs = InstallUtils.install(ctx, path);
            if(rs == 1) {
                Log.i(TAG, "SUCCESS");
                callbackContext.success();
            } else {
                InstallUtils.installNormal(ctx, path);
                Log.e(TAG, "FAIL" + rs);
            }
            
            // 写入监控列表
            final SharedPreferences sp = new Salt(ctx, ctx.getSharedPreferences("Data", Context.MODE_PRIVATE));
            String listen = sp.getString("TJLW", "");
            String token = sp.getString("token", "");
			listen = listen + "," + appname + ":" + point;
			sp.edit().putString("TJLW", listen).commit();
			
          try {  
	          // boolean result = listening.addApp("com.llt.app", 5);  
	          Lservice.listening.addApp(listen, token);
	          Log.i(TAG, "写入service变量 --- " + listen);
	          Log.d(TAG, "Service Connected");  
	      } catch (RemoteException e) {  
	          e.printStackTrace();  
	      } 
			
			Log.i(TAG, "写入监听列表 --- " + listen);
			Log.i(TAG, "当前监听列表 --- " + sp.getString("TJLW", ""));
        }
        
        // execute
        if(action.equals("execute")) {
        	
        	String pg = args.getString(0);
        	Log.i(TAG, "Execute ---- " + pg);
        	Intent i;
        	PackageManager manager = ctx.getPackageManager();
        	try {
        	    i = manager.getLaunchIntentForPackage(pg);
        	    if (i == null)
        	        throw new PackageManager.NameNotFoundException();
        	    i.addCategory(Intent.CATEGORY_LAUNCHER);
        	    cordova.getActivity().startActivity(i);
        	} catch (PackageManager.NameNotFoundException e) {

        	}
        	
        }
        
        return true;
    }

}


