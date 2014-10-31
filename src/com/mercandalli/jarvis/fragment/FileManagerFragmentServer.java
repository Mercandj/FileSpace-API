/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarvis.fragment;

import java.util.ArrayList;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;

import com.mercandalli.jarvis.Application;
import com.mercandalli.jarvis.R;
import com.mercandalli.jarvis.adapter.AdapterModelFile;
import com.mercandalli.jarvis.listener.IPostExecuteListener;
import com.mercandalli.jarvis.model.ModelFile;
import com.mercandalli.jarvis.net.TaskPost;

public class FileManagerFragmentServer extends Fragment {
	
	Application app;
	ListView files;
	List<ModelFile> listModelFile;
	
	public FileManagerFragmentServer(Application app) {
		this.app = app;	
	}	
	
	@Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_filemanager_fragment, container, false);        
        
        files = (ListView) rootView.findViewById(R.id.files);
        
        return rootView;
    }	
	
	public void refreshList() {
		Log.d("CC", "refreshList");
		JSONObject json = new JSONObject();
		try {
			json.put("user", this.app.config.getUser().getJsonRegister());
			
			new TaskPost(this.app.config.getUrlServer()+"file/get", new IPostExecuteListener() {
				@Override
				public void execute(JSONObject json, String body) {
					listModelFile = new ArrayList<ModelFile>();
					try {
						if(json!=null) {
							if(json.has("result")) {							
								JSONArray array = json.getJSONArray("result");
								for(int i=0; i<array.length();i++) {
									ModelFile modelFile = new ModelFile();
									JSONObject fileJson = array.getJSONObject(i);
									if(fileJson.has("url")) {
										modelFile.url = fileJson.getString("url");
										modelFile.name = fileJson.getString("url");
									}
									listModelFile.add(modelFile);
								}						
							}
						}
					} catch (JSONException e) {
						e.printStackTrace();
					}				
					updateAdapter();
				}			
			}, json).execute();
			
		} catch (JSONException e1) {
			e1.printStackTrace();
		}
	}
	
	private void updateAdapter() {
		if(files!=null && listModelFile!=null)
			files.setAdapter(new AdapterModelFile(app, R.layout.tab_file, listModelFile ));
	}
}
