/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarpis.net;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;

import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONObject;

import android.os.AsyncTask;
import android.util.Log;

import com.mercandalli.jarpis.model.User;

public class RegisterTask extends AsyncTask<String, Void, String>{
	
	User user;
	
	public RegisterTask(User user) {
		this.user = user;
	}	

    protected String doInBackground(String... urls) {
    	try {
	    	HttpPost httppost = new HttpPost(urls[0]);
	    	
			JSONObject holder = this.user.getJsonRegister();
			final String CODEPAGE = "UTF-8";
			httppost.setEntity(new StringEntity(holder.toString(), CODEPAGE));
			httppost.addHeader("Content-type", "application/json");
			
			HttpClient httpclient = new DefaultHttpClient();
			HttpResponse response = httpclient.execute(httppost);
			
			// receive response as inputStream
			InputStream inputStream = response.getEntity().getContent();
            
            // convert inputstream to string
            if(inputStream != null)
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

    protected void onPostExecute(String response) {
    	Log.d("COUCOU", ""+response);
    }
    
    /**
     * Get http response to String
     * @param inputStream
     * @return
     * @throws IOException
     */
    private String convertInputStreamToString(InputStream inputStream) throws IOException {
        BufferedReader bufferedReader = new BufferedReader( new InputStreamReader(inputStream));
        String line = "";
        String result = "";
        while((line = bufferedReader.readLine()) != null)
            result += line;
 
        inputStream.close();
        return result;
    }
}
