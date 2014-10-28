/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarpis;

import android.app.Activity;
import android.app.FragmentManager;
import android.os.Bundle;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;

public class MainActivity extends Activity {	
	
	public static Config config;
	
	public static final int TYPE_IC 		= 0;
	public static final int TYPE_NORMAL	 	= 1;
	public static final int TYPE_SECTION	= 2;

	private DrawerLayout mDrawerLayout;
    private ListView mDrawerList;
    public static NavDrawerItemListe navDrawerItems;
    
    myFragment fragment;
	
	public NavDrawerItem TAB_1;
	
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        
        config = new Config(this);
        
        setContentView(R.layout.activity_main);        
        
        mDrawerLayout = (DrawerLayout) findViewById(R.id.drawer_layout);
        mDrawerList = (ListView) findViewById(R.id.left_drawer);        
        
        mDrawerLayout.setDrawerShadow(R.drawable.drawer_shadow, GravityCompat.START);
        navDrawerItems = new NavDrawerItemListe();
        
        TAB_1 = new NavDrawerItem( "Resume", TYPE_NORMAL);
        navDrawerItems.add(TAB_1);
        
        
    	fragment = new myFragment();
        FragmentManager fragmentManager = getFragmentManager();
        fragmentManager.beginTransaction().replace(R.id.content_frame, fragment).commit();
        
        
        mDrawerList.setAdapter(new NavDrawerListAdapter(this, navDrawerItems.getListe()));
        mDrawerList.setOnItemClickListener(new DrawerItemClickListener());
        
    }
    
    private void selectItem(int position) {    	
    	for(NavDrawerItem nav : navDrawerItems.getListe())
    		if(navDrawerItems.get(position).equals(nav))
    			if(nav.listenerClick!=null)
    				if(nav.listenerClick.condition())
    					nav.listenerClick.execute();
    	
        mDrawerLayout.closeDrawer(mDrawerList);
    }
    
    /* The click listner for ListView in the navigation drawer */
    private class DrawerItemClickListener implements ListView.OnItemClickListener {
        @Override
        public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
            selectItem(position);
        }
    }
	
}
