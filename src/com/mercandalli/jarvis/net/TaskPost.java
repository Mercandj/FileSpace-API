/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarvis.net;

import java.io.BufferedReader;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;

import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import com.mercandalli.jarvis.Application;
import com.mercandalli.jarvis.R;
import com.mercandalli.jarvis.listener.IPostExecuteListener;

/**
 * Global behavior : http Post
 * 
 * @author Jonathan
 * 
 */
public class TaskPost extends AsyncTask<Void, Void, String> {

	String url;
	JSONObject json;
	IPostExecuteListener listener;
	File file;
	Application app;

	public TaskPost(Application app, String url, IPostExecuteListener listener, JSONObject json) {
		this.app = app;
		this.url = url;
		this.json = json;
		this.listener = listener;
	}

	public TaskPost(Application app, String url, IPostExecuteListener listener, JSONObject json, File file) {
		this.app = app;
		this.url = url;
		this.json = json;
		this.listener = listener;
		this.file = file;
	}

	@Override
	protected String doInBackground(Void... urls) {
		try {
			HttpPost httppost = new HttpPost(url);
						
			MultipartEntity mpEntity = new MultipartEntity();
			if (file != null) mpEntity.addPart("file", new FileBody(file, "*/*"));
			mpEntity.addPart("json", new StringBody(json.toString()));
			httppost.setEntity(mpEntity);
			
			StringBuilder authentication = new StringBuilder().append(app.config.getUser().getAccessLogin()).append(":").append(app.config.getUser().getAccessPassword());
	        String result = Base64.encodeBytes(authentication.toString().getBytes());
	        httppost.setHeader("Authorization", "Basic " + result);
			
			HttpClient httpclient = new DefaultHttpClient();
			HttpResponse response = httpclient.execute(httppost);

			// receive response as inputStream
			InputStream inputStream = response.getEntity().getContent();

			// convert inputstream to string
			if (inputStream != null)
				return convertInputStreamToString(inputStream);
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		} catch (ClientProtocolException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return null;
	}

	/**
	 * Get http response to String
	 * 
	 * @param inputStream
	 * @return
	 * @throws IOException
	 */
	private String convertInputStreamToString(InputStream inputStream) throws IOException {
		BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(inputStream));
		String line = "";
		String result = "";
		while ((line = bufferedReader.readLine()) != null)
			result += line;

		inputStream.close();
		return result;
	}

	@Override
	protected void onPostExecute(String response) {
		Log.d("onPostExecute", "" + response);
		if (response == null)
			this.listener.execute(null, null);
		else {
			try {
				JSONObject json = new JSONObject(response);				
				this.listener.execute(json, response);				
				if(json.has("toast"))										
					Toast.makeText(app, json.getString("toast"), Toast.LENGTH_SHORT).show();				
			} catch (JSONException e) {
				e.printStackTrace();
				Toast.makeText(app, app.getString(R.string.action_failed), Toast.LENGTH_SHORT).show();
				this.listener.execute(null, response);
			}
		}
	}
}
