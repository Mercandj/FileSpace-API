/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarpis;

import android.app.Dialog;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;

import com.mercandalli.jarpis.model.User;
import com.mercandalli.jarpis.net.RegisterTask;

public class MainActivity extends ApplicationDrawer {	
	
    @Override
    public void onCreate(Bundle savedInstanceState) {    	
    	setContentView(R.layout.activity_main);
        super.onCreate(savedInstanceState);
        
        dialog = new Dialog(this);
        dialog.setContentView(R.layout.view_login);
        dialog.setTitle(R.string.app_name);
        dialog.setCancelable(false);        
        
        ((Button) dialog.findViewById(R.id.signin)).setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {				
				User user = new User();
				user.username = "toto";
				user.password = "tata";
				(new RegisterTask(user)).execute("http://mercandalli.com/jarpis/php/register");
				
				dialog.dismiss();
			}        	
        });
        dialog.show();
    }    
    
}
