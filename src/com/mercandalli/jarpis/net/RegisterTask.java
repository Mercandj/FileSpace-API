/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarpis.net;

import android.util.Log;

import com.mercandalli.jarpis.model.User;

public class RegisterTask extends PostTask {
	
	User user;
	
	public RegisterTask(String url_server, User user) {
		super(url_server+"register", user.getJsonRegister());		
	}
    
    @Override
    protected void onPostExecute(String response) {
    	Log.d("COUCOU", ""+response);
    }
}
