package so.doo.app.plugins;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;

import org.apache.cordova.CallbackContext;
import org.apache.cordova.CordovaInterface;
import org.apache.cordova.CordovaPlugin;
import org.apache.cordova.CordovaWebView;
import org.json.JSONArray;
import org.json.JSONException;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
import android.util.Log;

@SuppressLint("HandlerLeak")
public class Utils extends CordovaPlugin {
    
    private Context ctx;
    private SharedPreferences sp;

     public void initialize(CordovaInterface cordova, CordovaWebView webView) {
        super.initialize(cordova, webView);
        ctx = cordova.getActivity().getApplicationContext();
        sp = new Salt(ctx, ctx.getSharedPreferences("Data", Context.MODE_PRIVATE) );
    }

    private JSONArray installed() {
        PackageManager packageMgr = ctx.getPackageManager();
        Intent mainIntent = new Intent(Intent.ACTION_MAIN, null);
        mainIntent.addCategory(Intent.CATEGORY_LAUNCHER);
        List<ResolveInfo> resovleInfos = packageMgr.queryIntentActivities(mainIntent, 0);

        ArrayList<String> list  = new ArrayList<String>();
        for (ResolveInfo resolve : resovleInfos) {
            String packageName = resolve.activityInfo.packageName;
            list.add(packageName);
        }
        List<String> ulist = new ArrayList<String>(new HashSet<String>(list));
        
        return new JSONArray(ulist);
    }
    
    // get token
    private String getToken() {
    	String token = sp.getString("token", "");
    	Log.i("TOKEN", "获取token ------ " + token);
    	
    	return token;
    }

    // set token
    private void setToken(String token) {
    	Log.i("TOKEN", "设置token ------ " + token);
    	sp.edit().putString("token", token).commit();
    	
    	Log.i("TOKEN", "已设置token -------- " + sp.getString("token", ""));
    }
    
    // get point
    private String getPoint() {
    	String point = sp.getString("point", "0");

    	return point;
    }
    
    // save point
    private void savePoint(String point) {
    	sp.edit().putString("point", point).commit();
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
    	
    	Log.i("Action", action);
        // installed app list
        if (action.equals("installed")) {
            JSONArray json = installed();
            callbackContext.success(json.toString());
            
            return true;
        }
        
        // get token
        if (action.equals("getToken")) {
            String token = getToken();
            Log.i("TOKEN", "TOKEN发送 -----" + token);
            
            callbackContext.success(token);
            
            return true;
        }
        
        // set token
        if (action.equals("setToken")) {
        	String token = args.getString(0);
        	Log.i("TOKEN", "TOKEN接收 -----" + token);
            setToken(token);
            
            return true;
        }
        
        // get point
        if (action.equals("getPoint")) {
            String point = getPoint();
            callbackContext.success(point);
            
            return true;
        }
        
        // save point
        if (action.equals("savePoint")) {
        	String point = args.getString(0);
        	savePoint(point);
            
            return true;
        }
        

        return false;
    }
}

