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
import android.widget.TextView;
import android.widget.Toast;

import com.mercandalli.jarvis.Application;
import com.mercandalli.jarvis.R;
import com.mercandalli.jarvis.listener.IModelFileListener;
import com.mercandalli.jarvis.listener.IPostExecuteListener;
import com.mercandalli.jarvis.model.ModelFile;
import com.mercandalli.jarvis.model.ModelUser;
import com.mercandalli.jarvis.net.TaskPost;

public class DialogUpload extends Dialog {
	
	DialogFileChooser dialogFileChooser;
	Application app;
	File file;
	ModelFile modelFile;
	
	public DialogUpload(final Application app, final IPostExecuteListener listener) {
		super(app);
		this.app = app;
		
		this.setContentView(R.layout.view_upload);
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
					if(file!=null && DialogUpload.this.modelFile != null)
						json.put("file", DialogUpload.this.modelFile.getJSONRequest());
				} catch (JSONException e1) {
					e1.printStackTrace();
				}
				
				if(file!=null)
					(new TaskPost(app, app.config.getUrlServer()+app.config.routeFilePost, new IPostExecuteListener() {
						@Override
						public void execute(JSONObject json, String body) {
							if(listener!=null)
								listener.execute(json, body);
						}						
					}, json, file)).execute();
				else
					Toast.makeText(app, app.getString(R.string.no_file), Toast.LENGTH_SHORT).show();
				
				DialogUpload.this.dismiss();
			}        	
        });
        
        ((Button) this.findViewById(R.id.fileButton)).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				dialogFileChooser = new DialogFileChooser(DialogUpload.this.app, new IModelFileListener() {
					@Override
					public void execute(ModelFile modelFile) {
						((TextView) DialogUpload.this.findViewById(R.id.label)).setText(""+modelFile.name);
						DialogUpload.this.file = new File(modelFile.url);
						DialogUpload.this.modelFile = modelFile;
					}					
				});
			}        	
        });        
        
        DialogUpload.this.show();
	}
}
