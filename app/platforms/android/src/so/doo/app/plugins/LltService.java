package so.doo.app.plugins;

import java.io.DataOutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.List;

import so.doo.app.plugins.Listening.Stub;
import android.app.ActivityManager;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageManager;
import android.os.AsyncTask;
import android.os.Binder;
import android.os.Handler;
import android.os.IBinder;
import android.os.RemoteException;
import android.util.Log;

public class LltService extends Service {

	public static final String TAG = "LltService";
	public Context ctx;
	private PackageManager pm;
	private String listen;
	private String token;

	private Handler handler = new Handler();
	private Runnable runnable = new Runnable() {
		@Override
		public void run() {
			new BackgroundTask(ctx).execute();
			handler.postDelayed(this, 10000);
		}
	};

	@Override
	public void onCreate() {
		super.onCreate();
		ctx = this.getApplicationContext();
		Log.d(TAG, "onCreate() executed");
	}

	@Override
	public int onStartCommand(Intent intent, int flags, int startId) {
		Log.d(TAG, "onStartCommand() executed");
		handler.postDelayed(runnable, 200);
		return super.onStartCommand(intent, flags, startId);
	}

	@Override
	public void onDestroy() {
		super.onDestroy();
		handler.removeCallbacks(runnable);
		Log.d(TAG, "onDestroy() executed");
	}

	@Override
	public IBinder onBind(Intent intent) {
		// TODO Auto-generated method stub

		return mBinder;
	}

	public class BackgroundTask extends AsyncTask<String, String, Void> {

		Context mContext = null;
		String response;

		public BackgroundTask(Context context) {
			mContext = context;
		}

		protected void onPreExecute() {
		}

		protected Void doInBackground(final String... args) {
			SharedPreferences sp = new Salt(ctx,
					ctx.getSharedPreferences("Data", Context.MODE_PRIVATE));
			String server = sp.getString("server", "");
			String imei = sp.getString("imei", "");

			Log.i(TAG, "����ing --- " + listen);
			if (listen != null && !listen.equals("")) {
				pm = ctx.getPackageManager();
				Log.i(TAG, "������Ҫ�����İ�...");
				String[] llist = listen.split(",");

				// ������������еİ�
				List<ApplicationInfo> listAppcations = pm
						.getInstalledApplications(PackageManager.GET_UNINSTALLED_PACKAGES);
				Collections.sort(listAppcations,
						new ApplicationInfo.DisplayNameComparator(pm));
				ActivityManager mActivityManager = (ActivityManager) getSystemService(Context.ACTIVITY_SERVICE);
				List<ActivityManager.RunningAppProcessInfo> appProcessList = mActivityManager
						.getRunningAppProcesses();

				String names = "";
				for (ActivityManager.RunningAppProcessInfo appProcess : appProcessList) {

					String[] pkgNameList = appProcess.pkgList; // ��������ڸý����������Ӧ�ó����
					for (int i = 0; i < pkgNameList.length; i++) {
						names = names + "," + pkgNameList[i];
					}
				}

				String[] allNames = names.split(",");

				List list = new ArrayList(Arrays.asList(allNames));
				for (int j = 1; j < llist.length; j++) {
					String[] app = llist[j].split(":");
					if (list.contains(app[0])) {
						String appname = app[0];
						if(sendDataToServer(server, imei, appname, token)) {
							Log.i(TAG, "�����б�");
							// ��ռ���б�
							listen = listen.replaceAll("," + app[0] + ":\\d+", "");
							SharedPreferences sb = new Salt(ctx,
									ctx.getSharedPreferences("Data", Context.MODE_PRIVATE));
							sb.edit().putString("TJLW", listen).commit();
							Log.i(TAG, "��ǰ����б� --- " + sb.getString("TJLW", ""));
						}

					}
				}
			}
			return null;
		}
	}

	public boolean sendDataToServer(String server, String imei, String  appname, String token) {

		Log.d("MATCH", "���������� ---- " + appname);

		Log.i(TAG, "URL ---- " + server + "/operate");
		Log.i(TAG, "package --- " + appname);
		Log.i(TAG, "action --- completed");
		Log.i(TAG, "token --- " + token);
		Log.i(TAG, "imei --- " + imei);

		try {
			Log.i("POST", "try post");
			String serverUrl = server + "/operate";
			URL url = new URL(serverUrl);
			HttpURLConnection conn = (HttpURLConnection) url.openConnection();
			conn.setDoInput(true);
			conn.setDoOutput(true);
			conn.setConnectTimeout(6 * 10 * 1000);
			conn.setRequestProperty("Content-Type",
					"application/x-www-form-urlencoded;charset=UTF-8");
			conn.setRequestMethod("POST");
			conn.connect();
			DataOutputStream dos = new DataOutputStream(conn.getOutputStream());
			String postContent = URLEncoder.encode("package", "UTF-8") + "="
					+ URLEncoder.encode(appname, "UTF-8");
			postContent += "&" + URLEncoder.encode("action", "UTF-8") + "="
					+ URLEncoder.encode("completed", "UTF-8");
			postContent += "&" + URLEncoder.encode("token", "UTF-8") + "="
					+ URLEncoder.encode(token, "UTF-8");
			postContent += "&" + URLEncoder.encode("imei", "UTF-8") + "="
					+ URLEncoder.encode(imei, "UTF-8");
			dos.write(postContent.getBytes());
			dos.flush();
			// ִ����dos.close()��POST�������
			dos.close();
			// ��ȡ���뷵��ֵ
			int respondCode = conn.getResponseCode();
			Log.i("POST", "����ֵ" + respondCode);
			// ��ȡ������������
			String type = conn.getContentType();
			Log.i("POST", "����������ֵ" + type);
			// ��ȡ�������ݵ��ַ�����
			String encoding = conn.getContentEncoding();
			Log.i("POST", "���ַ�����ֵ" + encoding);
			// ��ȡ�������ݳ��ȣ���λ�ֽ�
			int length = conn.getContentLength();
			Log.i("POST", "���ݳ��Ȼ�ֵ" + length);
			conn.disconnect();
			Log.i("POST", "POST���");
			
			return true;

		} catch (Exception e) {
			e.printStackTrace();
		}
		
		return false;

	}

	Listening.Stub mBinder = new Stub() {
		@Override
		public void addApp(String l, String t) throws RemoteException {
			listen = l;
			token = t;
			Log.i(TAG, "mBinder���ܱ���" + listen);
		}
	};

}
