/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarpis;

import com.mercandalli.jarpis.conf.Config;
import com.mercandalli.jarpis.fragments.MainFragment;
import com.mercandalli.jarpis.navdrawer.NavDrawerItem;
import com.mercandalli.jarpis.navdrawer.NavDrawerItemListe;
import com.mercandalli.jarpis.navdrawer.NavDrawerListAdapter;

import android.app.Activity;
import android.app.FragmentManager;
import android.content.res.Configuration;
import android.os.Bundle;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;

public class MainActivity extends Activity {	
	
	public Config config;
	
	public static final int TYPE_IC 		= 0;
	public static final int TYPE_NORMAL	 	= 1;
	public static final int TYPE_SECTION	= 2;

	private DrawerLayout mDrawerLayout;
    private ListView mDrawerList;
    public static NavDrawerItemListe navDrawerItems;
    private ActionBarDrawerToggle mDrawerToggle;
    
    MainFragment fragment;
	
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
        
    	fragment = new MainFragment();
        FragmentManager fragmentManager = getFragmentManager();
        fragmentManager.beginTransaction().replace(R.id.content_frame, fragment).commit();        
        
        getActionBar().setDisplayHomeAsUpEnabled(true);
        getActionBar().setIcon(R.drawable.transparent);
        
        mDrawerList.setAdapter(new NavDrawerListAdapter(this, navDrawerItems.getListe()));
        mDrawerList.setOnItemClickListener(new DrawerItemClickListener());
        
        mDrawerToggle = new ActionBarDrawerToggle(
                this,               
                mDrawerLayout,      
                R.string.app_name, 
                R.string.app_name  
                ) {
            public void onDrawerClosed(View view) {
            	super.onDrawerClosed(view);
                invalidateOptionsMenu();
            }

            public void onDrawerOpened(View drawerView) {
            	super.onDrawerOpened(drawerView);
                invalidateOptionsMenu();
            }
        };
        
        mDrawerLayout.setDrawerListener(mDrawerToggle);
    }
    
    private void selectItem(int position) {    	
    	for(NavDrawerItem nav : navDrawerItems.getListe())
    		if(navDrawerItems.get(position).equals(nav))
    			if(nav.listenerClick!=null)
    				if(nav.listenerClick.condition())
    					nav.listenerClick.execute();
    	
        mDrawerLayout.closeDrawer(mDrawerList);
    }
    
    private class DrawerItemClickListener implements ListView.OnItemClickListener {
        @Override
        public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
            selectItem(position);
        }
    }	
    
    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);
        mDrawerToggle.syncState();
    }

    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
        mDrawerToggle.onConfigurationChanged(newConfig);
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
    	boolean drawerOpen = mDrawerLayout.isDrawerOpen(mDrawerList);
        switch (item.getItemId()) {
        case android.R.id.home:
        	if(drawerOpen) 	mDrawerLayout.closeDrawer(mDrawerList);
        	else 			mDrawerLayout.openDrawer(mDrawerList);
        	return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
