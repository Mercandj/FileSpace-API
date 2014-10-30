/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarvis.dialog;

import java.io.File;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Dialog;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.mercandalli.jarvis.Application;
import com.mercandalli.jarvis.R;
import com.mercandalli.jarvis.listener.IModelFileListener;
import com.mercandalli.jarvis.listener.IPostExecuteListener;
import com.mercandalli.jarvis.model.ModelFile;
import com.mercandalli.jarvis.model.ModelUser;
import com.mercandalli.jarvis.net.TaskPost;

public class DialogRequest extends Dialog {
	
	DialogFileChooser dialogFileChooser;
	Application app;
	File file;
	ModelFile modelFile;
	
	public DialogRequest(final Application app, final IPostExecuteListener listener) {
		super(app);
		this.app = app;
		
		this.setContentView(R.layout.view_request);
		this.setTitle(R.string.app_name);
		this.setCancelable(true);
	    
        ((Button) this.findViewById(R.id.request)).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				ModelUser user = new ModelUser();				
				user.username = app.config.getUserUsername();
				user.password = app.config.getUserPassword();
				
				JSONObject json = new JSONObject();
				try {
					json.put("user", user.getJsonRegister());					
					if(!((EditText) DialogRequest.this.findViewById(R.id.json)).getText().toString().replace(" ", "").equals(""))
						json.put("content", new JSONObject(((EditText) DialogRequest.this.findViewById(R.id.json)).getText().toString()));
					if(file!=null && DialogRequest.this.modelFile != null)
						json.put("file", DialogRequest.this.modelFile.getJSONRequest());
				} catch (JSONException e1) {
					e1.printStackTrace();
				}
				
				if(!((EditText) DialogRequest.this.findViewById(R.id.server)).getText().toString().equals(""))
					(new TaskPost(app.config.getUrlServer()+((EditText) DialogRequest.this.findViewById(R.id.server)).getText().toString(), new IPostExecuteListener() {
						@Override
						public void execute(JSONObject json, String body) {
							listener.execute(json, body);
						}						
					}, json, file)).execute();
				DialogRequest.this.dismiss();
			}        	
        });
        
        ((Button) this.findViewById(R.id.fileButton)).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				dialogFileChooser = new DialogFileChooser(DialogRequest.this.app, new IModelFileListener() {
					@Override
					public void execute(ModelFile modelFile) {
						((TextView) DialogRequest.this.findViewById(R.id.label)).setText(""+modelFile.name);
						DialogRequest.this.file = new File(modelFile.url);
						DialogRequest.this.modelFile = modelFile;
					}					
				});
			}        	
        });        
        
        DialogRequest.this.show();
	}
}
