package so.doo.app.plugins;

import org.apache.cordova.CallbackContext;
import org.apache.cordova.CordovaInterface;
import org.apache.cordova.CordovaPlugin;
import org.apache.cordova.CordovaWebView;
import org.json.JSONArray;
import org.json.JSONException;

import android.annotation.SuppressLint;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.content.SharedPreferences;
import android.os.IBinder;
import android.os.RemoteException;
import android.util.Log;

@SuppressLint("HandlerLeak")
public class Lservice extends CordovaPlugin {
    
    private Context ctx;
    public static Listening listening;
    public static final String TAG = "LSERVICE";
    
    private ServiceConnection connection = new ServiceConnection() {  
  	  
        @Override  
        public void onServiceDisconnected(ComponentName name) {  
        }  
  
        @Override  
        public void onServiceConnected(ComponentName name, IBinder service) {  
        	listening = Listening.Stub.asInterface(service);
            try {
            	Log.d(TAG, "Service Connected");  
            	SharedPreferences sp = new Salt(ctx, ctx.getSharedPreferences("Data", Context.MODE_PRIVATE));
            	String listen = sp.getString("TJLW", "");
            	String token = sp.getString("token", "");
            	Log.i(TAG, "初始化service变量 --- " + listen);
            	listening.addApp(listen, token);
                
            } catch (RemoteException e) {  
                e.printStackTrace();  
            } 
        }  
    };

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
        // start
        if (action.equals("start")) {
            start();
            
            return true;
        }

        return false;
    }

    public void start() {
        Intent startIntent = new Intent(ctx, LltService.class); 
        cordova.getActivity().startService(startIntent);
        Intent bindIntent = new Intent(ctx, LltService.class);  
        cordova.getActivity().bindService(bindIntent, connection, ctx.BIND_AUTO_CREATE);
    }
}
