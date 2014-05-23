package so.doo.app.plugins;

import org.apache.cordova.CallbackContext;
import org.apache.cordova.CordovaInterface;
import org.apache.cordova.CordovaPlugin;
import org.apache.cordova.CordovaWebView;
import org.json.JSONArray;
import org.json.JSONException;

import android.content.Context;
import android.content.SharedPreferences;

public class Init extends CordovaPlugin {
	
	private static Context ctx;
    private String server, imei;

    
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

        // init
        if (action.equals("init")) {
            server = args.getString(0);
            imei   = args.getString(1);
            init(server, imei);
        }
        
        return true;
    }

    // 初始化
    public void init(String server, String imei) {
        final SharedPreferences sp = new Salt(ctx, ctx.getSharedPreferences("Data", Context.MODE_PRIVATE));
        String locked = sp.getString("locked", "");
        if(locked.equals("")) {
            sp.edit().putString("server", server).putString("locked", "true").putString("imei", imei).putString("token", "").putString("TJLW", "").commit();
        }
    }
}

