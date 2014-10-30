package com.mercandalli.jarvis.adapter;

import java.util.List;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.mercandalli.jarvis.Application;
import com.mercandalli.jarvis.R;
import com.mercandalli.jarvis.model.ModelFile;

public class ModelFileAdapter extends ArrayAdapter<ModelFile> {

	Application app;
	List<ModelFile> files;
	
	public ModelFileAdapter(Application app, int resource, List<ModelFile> files) {
		super(app, resource, files);
		this.app = app;
		this.files = files;
	}
	
	@Override
	public View getView(int position, View convertView, ViewGroup parent) {		
		LayoutInflater inflater = app.getLayoutInflater();
		
		if(position<files.size()) {		
			final ModelFile file = files.get(position);			
			convertView = inflater.inflate(R.layout.tab_file, parent, false);
			
			((TextView) convertView.findViewById(R.id.title)).setText(file.name);
		}
		return convertView;
	}
	
	@Override
	public int getCount() {
		return files.size();
	}
}
